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
     * @var \Project\Application
     */
    public static $instance;
    
    /**
     * Запрос
     * @var \Project\Request 
     */
    protected $request;
    
    /**
     * Роутер
     * @var \Project\Router
     */
    protected $router;
    
    
    /**
     * Диспетчер
     * @var \Project\Dispatcher
     */
    protected $dispatcher;
    
    /**
     * Представление
     * @var \Project\View
     */
    protected $view;

    /**
     * Конструирование объекта
     */
    public function __construct()
    {
        $this->request = new \Project\Request();
       
        $this->router = new \Project\Router($this->request);
        
        $this->dispatcher = new \Project\Dispatcher($this->router, $this->request);
     }

    /**
     * Создание синглтона
     * @return \Project\Application
     */
    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Application();
        }
        
        return self::$instance;
    }
    
    /**
     * Go!
     */
    public function start()
    {
        $result = $this->dispatcher->dispatch();
        $this->view = new \Project\View($result, $this->request);       
    }
    
    /**
     * @return \Project\Request
     */
    function getRequest(): Request
    {
        return $this->request;
    }
    
    
    /**
     * @return \Project\Router
     */
    function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @return \Project\Dispatcher
     */
    function getDispatcher(): Dispatcher
    {
        return $this->dispatcher;
    }

    /**
     * @return \Project\View
     */
    function getView(): View
    {
        return $this->view;
    }

    /**
     * @param Request $request
     */
    function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param Router $router
     */
    function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @param Dispatcher $dispatcher
     */
    function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param View $view
     */
    function setView(View $view)
    {
        $this->view = $view;
    }
    
}
