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
     * @var Router
     */
    protected $router;
    
    /**
     * @var Request
     */
    protected $request;
    
    /**
     * @var array
     */
    protected $route;

    /**
     * Создание диспетчера
     * @param \Project\Router $router
     * @param \Project\Request $request
     */
    function __construct(Router $router, Request $request)
    {
        spl_autoload_register([$this, 'autoload']);
        
        $this->router = $router;
        
        $this->request = $request;
    }
    
    /**
     * Диспетчеризация
     * 
     * Здесь накосячено немного
     * @return mixed
     * @throws \Exception
     */
    public function dispatch()
    {
        $route = $this->router->getRoute($this->request->getRequestUri());
        $controllerClassName = $route['controller'];
        $action = $route['action'];
        
        if (!class_exists($controllerClassName)) {
            $this->request
                ->setParam('component', 'index')
                ->setParam('controller', 'error') 
                ->setParam('action', 'error');
            include_once APPLICATION_PATH . '/mvc/components/index/controllers/Error.php';
            $error = new \Project\Index\Controller\Error();
            return $error->error();
        }
        
        $controller = new $controllerClassName($this->request);
        
        if (!method_exists($controller, $action)) {
            $this->request
                ->setParam('component', 'index')
                ->setParam('controller', 'error') 
                ->setParam('action', 'error');
            include_once APPLICATION_PATH . '/mvc/components/index/controllers/Error.php';
            $error = new \Project\Index\Controller\Error();
            return $error->error();
        }
       
        $controllerClassNameParts = explode('\\', $controllerClassName);
        $controllerName = array_pop($controllerClassNameParts);
        
        $this->request
                ->setParam('component', $route['component'])
                ->setParam('controller', strtolower($controllerName))
                ->setParam('action', $action);
        
        spl_autoload_unregister([$this, 'autoload']);
        
        return $controller->{$action}();
    }
    
    /**
     * Автозагрузчик контроллеров
     */
    public function autoload($class)
    {
        $pathParts = explode('\\', $class);
        
        if (!in_array('Controller', $pathParts)) {
            return;
        }
        
        $component = $pathParts[1];
        $controller = $pathParts[3];
        
        $fileController = __DIR__ . '/components/' . strtolower($component)
                . '/controllers/' . $controller . '.php';
        
        if (!file_exists($fileController)) {
            $msg = 'Файл контроллера не найден: ' . $fileController;
            throw new \Exception($msg);
        }
        
        include_once $fileController;

    }
}
