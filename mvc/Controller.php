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
 * @category   Project
 * @package    Controller
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class Controller
{
    /**
     *
     * @var \Project\Request
     */
    protected $request;
    
    protected $isAuth;

    protected $user;

    protected $resources = [
        'Model' => 'models'
    ];
            
    function __construct(\Project\Request $request)
    {
        $this->request = $request;
        
        spl_autoload_register([$this, 'autoload'], true, true);
        
        $this->isAuth = $this->isAuth();
        
        $this->init();
    }
    
    protected function init(){}

    public function autoload($class)
    {
        $classParts = explode('\\', $class);
        
        $component = $classParts[1];
        
        $componentPath = APPLICATION_PATH . '/mvc/components/'
                . strtolower($component);
        
        if (!isset($classParts[2])) {
            return;
        }
        $resourcePath = $this->getResourcePath($classParts[2]);
        if (!$resourcePath) {
            return;
        }
        
        $className = array_pop($classParts);
        
        $classPath = $componentPath . DIRECTORY_SEPARATOR 
                . $resourcePath . DIRECTORY_SEPARATOR . $className . '.php';
        
        if (!file_exists($classPath)) {
            return;
        }
        
        include_once $classPath;
    }
    
    protected function getResourcePath($resourceName)
    {
        return isset($this->resources[$resourceName]) 
        ? $this->resources[$resourceName] : null;
    }
    
    protected function isAuth()
    {
        if (!isset($_SESSION['user'])) {
            return;
        }
        $user = $_SESSION['user'];
        if (isset($user->id) && isset($user->login)) {
            $conds = [
                'id' => filter_var($user->id, FILTER_VALIDATE_INT),
                'login' => filter_var($user->login, FILTER_VALIDATE_REGEXP, [
                         'options' => ['regexp' => '/^([a-zA-Z0-9\_\-\$\@\!]+)/']
                    ])
            ];
            $userModel = new \Project\Index\Model\User();
            $this->user = $userModel->get((object) $conds);
            if ($this->user) {
                return true;
            }
        }
    }
    
    protected function redirect($url = null)
    {
        header('Location: //' . $this->request->getHost() . $url);
        exit;
    }
}
