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

namespace Project\Index\Model;

/**
 *
 * 
 * @category   
 * @package    User
 * @author Gregory V Lominoga aka Gromodar <@gromodar at telegram>, Symedia Ltd
 */
class User extends \Project\Model
{
    public function get($cond)
    {
        $sql = "SELECT * FROM `users` ";
        if (isset($cond->id)) {
            $binds[] = "`id` = {$cond->id}";
            $useIndex = 'id';
        }
        if (isset($cond->login)) {
            $binds[] = "`login` = '{$cond->login}'";
            $useIndex = 'idLogin';
        }
        if (isset($cond->password)) {
            $binds[] = "`password` = '{$cond->password}'";
        }
        $sql .= str_replace('?', $useIndex, " USE INDEX (`?`) ");
        $sql .= " WHERE ";
        $sql .= implode(" AND ", $binds);
        return $this->db->query($sql)->fetch_object();
    }
    
    public function save($user)
    {
        $sql = "UPDATE `users` "
                . "SET `balance` = '{$user->balance}', "
                . "`spend` = '{$user->spend}', "
                . "`hash` = '{$user->hash}' "
                . "WHERE `id` = '{$user->id}'";
                //exit($sql);
        return $this->db->query($sql);
    }
    
    public function getUsers($id)
    {
        $sql = "SELECT * FROM `users` WHERE `id` <> {$id}";
        return $this->db->query($sql);
    }
    
    public function transaction($user, $id, $spend)
    {
        $this->db->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
        $this->db->query("SELECT * FROM `users` WHERE `id` = {$user->id} FOR UPDATE");
        $this->db->query("SELECT * FROM `users` WHERE `id` = {$id} FOR UPDATE");
        $this->db->query("UPDATE `users` SET `balance` = `balance` - {$spend} WHERE `id` = {$user->id}");
        $this->db->query("UPDATE `users` SET `balance` = `balance` + {$spend} WHERE `id` = {$id}");
        $this->db->commit();
        
    }
}
