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
use Project\View;
use Project\Model\User;

/**
 * @package Project
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class Controller
{

    /**
     * @var Request
     */
    protected $request;

    /**
     *
     * @var Responce
     */
    protected $responce;

    /**
     * Флаг аутентификации
     * @var bollean
     */
    protected $isAuth;

    /**
     * Данные аутентифицированного пользователя
     * @var object
     */
    protected $user;

    /**
     * @var View
     */
    protected $view;

    function __construct(Request $request, Responce $responce)
    {
        $this->view = new View($request);

        $this->request = $request;

        $this->responce = $responce;

        $this->isAuth = $this->isAuth();

        $this->init();
    }

    protected function init()
    {
    }

    /**
     * Аутентификация
     * @return boolean
     */
    protected function isAuth()
    {
        if (!isset($_SESSION['user'])) {
            return;
        }
        $user = $_SESSION['user'];
        if (isset($user->id) && isset($user->login)) {
            $id = filter_var($user->id, FILTER_VALIDATE_INT);
            $login = filter_var($user->login, FILTER_VALIDATE_REGEXP, [
                'options' => ['regexp' => '/^([a-zA-Z0-9\_\-\$\@\!]+)/']
            ]);
            $userModel = User::getInstance();
            $this->user = $userModel->getIdentity($id, $login);
            if ($this->user) {
                return true;
            }
        }
    }

    public function render()
    {
        $this->responce->setBody($this->view->render());
    }

    /**
     * Переадресация
     * @param string $url
     */
    protected function redirect($url = null)
    {
        header('Location: //' . $this->request->getHost() . $url);
        exit;
    }

}
