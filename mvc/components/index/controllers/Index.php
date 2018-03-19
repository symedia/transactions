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
 * Контроллер по умолчанию
 * 
 * @category   
 * @package    Default
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class Index extends \Project\Controller
{

    protected function init()
    {
        if (!$this->isAuth) {
            $this->redirect('/login');
        }
    }

    public function index()
    {
        $userModel = new \Project\Index\Model\User;

        $data = [
            'user' => $this->user,
            'users' => $userModel->getUsers($this->user->id)
        ];

        if ($this->request->isPost()) {

            $id = filter_var($this->request->post('id'), FILTER_VALIDATE_INT);
            if (false === $id) {
                $data['msg']['id'] = 'Необходимо выбрать пользователя!';
            }

            $spend = filter_var($this->request->post('spend'), FILTER_VALIDATE_INT);
            if (0 > $spend || $spend > $this->user->balance || false === $spend) {
                $data['msg']['spend'] = 'Неверная сумма списания!';
            }

            if (isset($data['msg'])) {
                return $data;
            }

            $result = $userModel->transaction($this->user, $id, $spend);
            $this->redirect();
        }

        return $data;
    }

}
