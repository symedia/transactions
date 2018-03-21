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

namespace Project\Controller;

use Project\Controller;
use Project\Model\User;

/**
 * Контроллер по умолчанию
 *
 * @category Controller
 * @package Project
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class Index extends Controller
{

    protected function init()
    {
        if (!$this->isAuth) {
            $this->redirect('/login');
        }
    }

    public function index()
    {
        $userModel = User::getInstance();

        $this->view->user = $this->user;
        $this->view->users = $userModel->getUsers($this->user->id);

        if ($this->request->isPost()) {

            $amount = filter_var($this->request->post('amount'), FILTER_VALIDATE_FLOAT);
            if (false === $amount) {
                $this->view->msg = ['amount' => 'Неверная сумма списания!'];
                return;
            }

            $destinationUserId
                    = filter_var($this->request->post('id'), FILTER_VALIDATE_INT);
            if (false === $destinationUserId) {
                $this->view->msg = ['id' => 'Необходимо выбрать пользователя!'];
                return;
            }

            $userModel->transaction();

            $destinationUser = $userModel->getUser($destinationUserId);
            if (!$destinationUser) {
                $this->view->msg = ['id' => 'Пользователя не существует!'];
                $userModel->closeTransaction();
                return;
            }

            $senderUser = $userModel->getUser($this->user->id);

            $senderBalance = $senderUser->balance - $amount;
            if ($senderBalance < 0) {
                $this->view->msg = ['amount' => 'Не хватает средств на счете!'];
                $userModel->closeTransaction();
                return;
            }

            $destinationBalance = $destinationUser->balance + $amount;

            $userModel->updateBalance($senderUser->id, $senderBalance);
            $userModel->updateBalance($destinationUser->id, $destinationBalance);

            $userModel->transactionCommit();

            $this->redirect();
        }
    }

}
