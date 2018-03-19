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

/**
 * Оснонвной класс приложения.
 * 
 * @category   
 * @package    Application
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class Application
{
    /**
     * Синглтон класса
     * @var Application
     */
    public static $instance;
    
    /**
     * Запрос
     * @var Request 
     */
    protected $request;
    
    /**
     * Роутер
     * @var Router
     */
    protected $router;
    
    
    /**
     * Диспетчер
     * @var Dispatcher
     */
    protected $dispatcher;
    
    /**
     * Представление
     * @var View
     */
    protected $view;

    public function __construct()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
        
        $this->request = new Request();
        
        $this->router = new Router($this->request);
        
        $this->dispatcher = new Dispatcher($this->router);
        
        $result = $this->dispatcher->dispatch();
        
        $this->view = new View($result);
    }

    public static function start()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Application();
        }
        
        return self::$instance->view->render();
    }
    
    function getRequest(): Request
    {
        return $this->request;
    }

    function getRouter(): Router
    {
        return $this->router;
    }

    function getDispatcher(): Dispatcher
    {
        return $this->dispatcher;
    }

    function getView(): View
    {
        return $this->view;
    }

    function setRequest(Request $request)
    {
        $this->request = $request;
    }

    function setRouter(Router $router)
    {
        $this->router = $router;
    }

    function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    function setView(View $view)
    {
        $this->view = $view;
    }
    
    public function autoload($class)
    {
        
    }

}
