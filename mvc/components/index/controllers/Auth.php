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

/**
 *
 * 
 * @category   
 * @package    Auth
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class Auth extends \Project\Controller
{
    public function index()
    {
        if ($this->isAuth) {
            $this->redirect();
        }
        
        $data = [];
        if ($this->request->isPost() && !$this->isAuth) {
            $data['login'] = filter_var($this->request->post('login'), FILTER_SANITIZE_STRING);
            $data['password'] = filter_var($this->request->post('password'), 
                    FILTER_VALIDATE_REGEXP, [
                         'options' => ['regexp' => '/^([a-zA-Z0-9\_\-\$\@\!]+)/']
                    ]);
            
            $userModel = new \Project\Index\Model\User();
            $user = $userModel->get((object)$data);
            unset($user->password);
            if ($user) {
                $_SESSION['user'] = $user;
                session_write_close();
                $this->redirect();
            }
        }
        return $data;
    }
    
    public function logout()
    {
        unset($_SESSION['user']);
        $this->redirect();
    }
}
