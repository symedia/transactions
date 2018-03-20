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

namespace Project\Index\Controller;

use Project\Controller;
use Project\Index\Model\User;

/**
 * Контроллер авторизации
 * 
 * @category   
 * @package    Auth
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class Auth extends Controller
{
    /**
     * Авторизация
     * @return array
     */
    public function index()
    {
        if ($this->isAuth) {
            $this->redirect();
        }
        
        if ($this->request->isPost()) {
            $login = filter_var($this->request->post('login'), FILTER_SANITIZE_STRING);
            $password = filter_var($this->request->post('password'), 
                FILTER_VALIDATE_REGEXP, [
                     'options' => ['regexp' => '/^([a-zA-Z0-9\_\-\$\@\!]+)/']
                ]);
            
            $userModel = new User();
            $user = $userModel->authenticate($login, $password);
            if ($user) {
                session_start();
                session_regenerate_id(true);
                $_SESSION['user'] = $user;
                session_write_close();
                $this->redirect();
            }
        }
    }
    
    /**
     * Разлогин
     */
    public function logout()
    {
        if (!$this->isAuth) {
            $this->redirect();
        }
        session_start();
        session_regenerate_id(true);
        session_destroy();
        $this->redirect();
    }
}
