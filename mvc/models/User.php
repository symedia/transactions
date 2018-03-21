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

namespace Project\Model;

use Project\Model;
use Exception;
use mysqli;

/**
 * Работа с данными пользователя
 *
 * @category Model
 * @package Project
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class User
{
    /**
     * Singleton instance
     *
     * @var User
     */
    protected static $instance = null;

    /**
     * @var mysqli
     */
    protected $mysqli;

    protected function __construct()
    {

        $this->mysqli = new mysqli('localhost', 'root', '123', 'task');

        if ($this->mysqli->connect_error) {
            throw new Exception(
            $this->mysqli->connect_error, $this->mysqli->connect_errno
            );
        }
    }

    /**
     * Singleton instance
     *
     * @return Model
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Возвращает объект данных пользователя по заданному id
     * @param int $id
     * @return object
     * @throws Exception
     */
    public function getUser($id)
    {
        $sql = "SELECT * FROM `users` WHERE `id` = ? FOR UPDATE";

        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            $msg = 'Не удалось подготовить запрос: (' . $this->errno . ') '
                    . $this->error;
            throw new Exception($msg);
        }

        if (!$stmt->bind_param('i', $id)) {
            $msg = 'Не удалось задать значения: (' . $stmt->errno . ') '
                    . $stmt->error;
            throw new Exception($msg);
        }

        if (!$stmt->execute()) {
            $msg = 'Не удалось выполнить запрос: (' . $stmt->errno . ') '
                    . $stmt->error;
            throw new Exception($msg);
        }

        $res = $stmt->get_result();
        if (!$res) {
            $msg = 'Не удалось получить результат: (' . $stmt->errno . ') '
                    . $stmt->error;
            throw new Exception($msg);
        }

        return $res->fetch_object();
    }

    /**
     * Аутентификация пользователя по логину и паролю
     * Возвращает объект с идентификационными данными пользователя
     * @param string $login
     * @param string $password
     * @return object
     * @throws Exception
     */
    public function authenticate($login, $password)
    {
        $sql = "SELECT `id`, `login` FROM `users` WHERE `login` = ? AND `password` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            $msg = 'Не удалось подготовить запрос: (' . $this->errno . ') '
                    . $this->error;
            throw new Exception($msg);
        }

        if (!$stmt->bind_param('ss', $login, $password)) {
            $msg = 'Не удалось задать значения: (' . $stmt->errno . ') '
                    . $stmt->error;
            throw new Exception($msg);
        }

        if (!$stmt->execute()) {
            $msg = 'Не удалось выполнить запрос: (' . $stmt->errno . ') '
                    . $stmt->error;
            throw new Exception($msg);
        }

        $res = $stmt->get_result();
        if (!$res) {
            $msg = 'Не удалось получить результат: (' . $stmt->errno . ') '
                    . $stmt->error;
            throw new Exception($msg);
        }

        return $res->fetch_object();
    }

    /**
     * Возвращает объект с данными пользователя по идентификационным данным id и login
     * @param int $id
     * @param string $login
     * @return object
     * @throws Exception
     */
    public function getIdentity($id, $login)
    {
        $sql = "SELECT `id`, `login`, `balance` FROM `users` USE INDEX (`idLogin`) "
                . "WHERE `id` = ? AND `login` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            $msg = 'Не удалось подготовить запрос: (' . $this->errno . ') '
                    . $this->error;
            throw new Exception($msg);
        }

        if (!$stmt->bind_param('is', $id, $login)) {
            $msg = 'Не удалось задать значения: (' . $stmt->errno . ') '
                    . $stmt->error;
            throw new Exception($msg);
        }

        if (!$stmt->execute()) {
            $msg = 'Не удалось выполнить запрос: (' . $stmt->errno . ') '
                    . $stmt->error;
            throw new Exception($msg);
        }

        $res = $stmt->get_result();
        if (!$res) {
            $msg = 'Не удалось получить результат: (' . $stmt->errno . ') '
                    . $stmt->error;
            throw new Exception($msg);
        }

        return $res->fetch_object();
    }

    /**
     * Получить список пользователей кроме одного
     * @param int $id Id пользователя для исключения
     * @return \mysqli_result|false
     * @throws Exception
     */
    public function getUsers($id)
    {
        $sql = "SELECT * FROM `users` WHERE `id` <> ?";

        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            $msg = 'Не удалось подготовить запрос: (' . $this->errno . ') '
                    . $this->error;
            throw new Exception($msg);
        }

        if (!$stmt->bind_param('i', $id)) {
            $msg = 'Не удалось задать значения: (' . $stmt->errno . ') '
                    . $stmt->error;
            throw new Exception($msg);
        }

        if (!$stmt->execute()) {
            $msg = 'Не удалось выполнить запрос: (' . $stmt->errno . ') '
                    . $stmt->error;
            throw new Exception($msg);
        }

        $res = $stmt->get_result();
        if (!$res) {
            $msg = 'Не удалось получить результат: (' . $stmt->errno . ') '
                    . $stmt->error;
            throw new Exception($msg);
        }

        return $res;
    }

    /**
     * Начало транзакции
     */
    public function transaction()
    {
        $this->mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
    }

    /**
     * Совершение транзакции
     */
    public function transactionCommit()
    {
        $this->mysqli->commit();
    }

    /**
     * Обновление баланса
     * @param int $id Пользователя
     * @param float $balance
     */
    public function updateBalance($id, $balance)
    {
        $sql = "UPDATE `users` SET `balance` = ? WHERE `id` = ?";

        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            $msg = 'Не удалось подготовить запрос: (' . $this->errno . ') '
                    . $this->error;
            throw new Exception($msg);
        }

        if (!$stmt->bind_param('di', $balance, $id)) {
            $msg = 'Не удалось задать значения: (' . $stmt->errno . ') '
                    . $stmt->error;
            throw new Exception($msg);
        }

        if (!$stmt->execute()) {
            $msg = 'Не удалось выполнить запрос: (' . $stmt->errno . ') '
                    . $stmt->error;
            throw new Exception($msg);
        }
    }

}
