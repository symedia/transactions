<?php

/**
 * The MIT License
 *
 * Copyright 2018 Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Project;

use Project\Router;
use Project\Request;
use Project\Index\Controller\Error;

/**
 *
 * 
 * @category   
 * @package    Dispatcher
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class Dispatcher
{
    /**
     * @var \Project\Router
     */
    protected $router;
    
    /**
     * @var \Project\Request
     */
    protected $request;
    
    /**
     * @var array
     */
    protected $route;
    
    /**
     *
     * @var \Project\Controller
     */
    protected $controller;

    /**
     * Создание диспетчера
     * @param \Project\Router $router
     * @param \Project\Request $request
     */
    function __construct(Router $router, Request $request)
    {
        $this->router = $router;
        
        $this->request = $request;
    }
    
    /**
     * Диспетчеризация
     * 
     * Здесь накосячено немного
     * @return mixed
     */
    public function dispatch()
    {
        $route = $this->router->getRoute($this->request->getRequestUri());
        $controllerClassName = $route['controller'];
        $action = $route['action'];

        $controllerClassNameParts = explode('\\', $controllerClassName);
        $controllerName = array_pop($controllerClassNameParts);

        $this->request
                ->setParam('component', $route['component'])
                ->setParam('controller', strtolower($controllerName))
                ->setParam('action', $action);
        
        if ($this->isDispatchable($controllerClassName, $action)) {
            $result = $this->controller->{$action}();
        } else {
            $result = $this->error404();
        }
        
        return $result;
    }
    
    protected function isDispatchable($controllerClassName, $action)
    {
        if (class_exists($controllerClassName)) {
            $this->controller = new $controllerClassName($this->request);
            if (method_exists($this->controller, $action)) {
                return true;
            }            
        }
    }
    
    protected function error404()
    {
        $this->request
            ->setParam('component', 'index')
            ->setParam('controller', 'error') 
            ->setParam('action', 'error');
        $error = new Error();
        return $error->error();
    }
}
