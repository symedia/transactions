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

use Project\Request;
use Exception;

/**
 * @package Project
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class View
{
    /**
     * @var array
     */
    protected $vars = [];

    /**
     * @var Request
     */
    protected $request;

    protected $fileTemplate;

    protected $layoutPath;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->layoutPath = APPLICATION_PATH . '/mvc/views/layout.php';

        if (!file_exists($this->layoutPath)) {
            $msg = 'Файл шаблона не найден: ' . $this->layoutPath;
            throw new Exception($msg);
        }

        $controller = $this->request->getParams('controller');
        $action = $this->request->getParams('action');

        $this->fileTemplate = APPLICATION_PATH . '/mvc/views/' . $controller
                . DIRECTORY_SEPARATOR . $action . '.php';

        if (!file_exists($this->fileTemplate)) {
            $msg = 'Файл макета не найден: ' . $this->fileTemplate;
            throw new Exception($msg);
        }
    }

    /**
     * Подключение файла макета
     */
    public function content()
    {
        require $this->fileTemplate;
    }

    /**
     * Подключение главного файла шаблона
     */
    public function render()
    {
        require_once $this->layoutPath;
    }

    /**
     * Хелпер для форматирования
     * @param float $number
     * @return string
     */
    public function rub($number)
    {
        return number_format($number, 2, '.', ' ');
    }

    /**
     * Возвращает значения свойств переданные при выполнении контроллера
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }

    /**
     * Устанавливает значения свойств для использования в макете
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value = null)
    {
        $this->$name = $value;
    }

    /**
     * Проверяет наличие элемента
     * @param string $name
     * @return bollean
     */
    public function __isset($name)
    {
        return isset($this->$name);
    }

}
