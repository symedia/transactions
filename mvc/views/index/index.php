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

?>

<h1>Приветствуем <?= $this->user->login ?>!</h1>
<p><a href="/logout">Выход</a></p>

<p>Сейчас на вашем счете: <?= $this->rub($this->user->balance) ?> р.</p>

<h2>Вы можете перевести сумму любому пользователю:</h2>

<form action="" method="post">
  <dl>
    <dt><label for="id">Пользователь:</label></dt>
    <dd><select name="id" id="id" style="width:150px;box-sizing:border-box;">
        <?php while($user = $this->users->fetch_object()): ?>
        <option value="<?= $user->id ?>"<?php if ($this->id == $user->id): ?> selected<?php endif; ?>><?= $user->login ?></option>
        <?php endwhile; ?>
      </select>
      <?php if (isset($this->msg['id'])): ?>
      <p style="color:red;"><?= $this->msg['id'] ?></p>
      <?php endif; ?>
    </dd>
    <dt><label for="amount">Сумма для перевода:</label></dt>
    <dd>
      <input type="input" value="<?= $this->spend ?>" name="amount" style="width:150px;box-sizing:border-box;">
      <?php if (isset($this->msg['amount'])): ?>
      <p style="color:red;"><?= $this->msg['amount'] ?></p>
      <?php endif; ?>
    </dd>
  </dl>
  <p><input type="submit" value="Перевести"></p>
</form>

