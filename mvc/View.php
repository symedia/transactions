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
 *
 * 
 * @category   
 * @package    View
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class View
{
    protected $result;

    protected $request;
    
    protected $componentPath;

    /**
     * @param \Project\Request $request
     * @param array $result
     */
    public function __construct(Request $request, $result = null)
    {
        $this->result = $result;
        $this->request = $request;
        
        $component = $this->request->getParams('component');
        $this->componentPath = APPLICATION_PATH 
                . '/mvc/components/' . $component;
        
        $this->layout();
    }
    
    public function content()
    {
        $controller = $this->request->getParams('controller');
        $action = $this->request->getParams('action');
        
        $fileTemplate = $this->componentPath. '/views/' . $controller  
                . DIRECTORY_SEPARATOR . $action . '.php';
        
        if (!file_exists($fileTemplate)) {
            $msg = 'Файл макета не найден: ' . $fileTemplate;
            throw new Exception($msg);
        }
        
        require $fileTemplate;       
    }
    
    public function __get($name)
    {
        return isset($this->result[$name]) ? $this->result[$name] : null;
    }
    
    protected function layout()
    {
        $layoutPath = $this->componentPath . '/views/layout.php';

        if (!file_exists($layoutPath)) {
            $msg = 'Файл шаблона не найден: ' . $layoutPath;
            throw new Exception($msg);
        }
        
        require_once $layoutPath;
    }
    
    public function rub($number)
    {
        return number_format($number, 2, '.', ' ');
    }
}
