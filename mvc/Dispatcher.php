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
use Project\Responce;
use Project\Controller;
use Project\Controller\Error;

/**
 * @package Project
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class Dispatcher
{

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Request|null
     */
    protected $request = null;

    /**
     * @var Responce|null
     */
    protected $responce = null;

    /**
     * @var Controller
     */
    protected $controller;

    /**
     * Создание диспетчера
     * @param Router $router
     * @param Request $request
     */
    function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Диспетчеризация
     */
    public function dispatch(Request $request, Responce $responce)
    {
        $this->request = $request;
        $this->responce = $responce;

        $route = $this->router->getRoute($this->request->getRequestUri());

        $controllerClass = $route['controller'];
        $action = $route['action'];

        $controllerClassName
                = substr($controllerClass, strrpos($controllerClass, '\\') + 1);

        $this->request
                ->setParam('controller', strtolower($controllerClassName))
                ->setParam('action', $action);

        if ($this->isDispatchable($controllerClass, $action)) {
            $this->controller->{$action}();
            $this->controller->render();
        }
        $this->error404();
    }

    protected function isDispatchable($controllerClass, $action)
    {
        if (class_exists($controllerClass)) {
            $this->controller
                    = new $controllerClass($this->request, $this->responce);
            if (is_callable([$this->controller, $action])) {
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
        $error = new Error($this->request, $this->responce);
        $error->error();
        $error->render();
    }

}
