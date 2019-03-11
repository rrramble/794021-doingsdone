<?php
    $email = $data['postEmail'] ?? '';
    $emailErrorMessage = $data['emailErrorMessage'] ?? '';
    $emailErrorCssClass = $emailErrorMessage ? 'form__input--error' : '';

    $userName = $data['postUserName'] ?? '';
    $userNameErrorMessage = $data['userNameErrorMessage'] ?? '';
    $userNameCssClass = $userNameErrorMessage ? 'form__input--error' : '';
?>
<h2 class="content__main-heading">Регистрация аккаунта</h2>

<form class="form" action="/pages/register.php" method="post">
  <div class="form__row">
    <label class="form__label" for="email">E-mail <sup>*</sup></label>
    <input class="form__input <?= $emailErrorCssClass; ?>" type="text" name="email" id="email" value="<?= $email; ?>" placeholder="Введите e-mail">
    <p class="form__message"><?= $emailErrorMessage; ?></p>
  </div>

  <div class="form__row">
    <label class="form__label" for="password">Пароль <sup>*</sup></label>
    <input class="form__input" type="password" name="password" id="password" value="" placeholder="Введите пароль">
  </div>

  <div class="form__row">
    <label class="form__label" for="name">Имя <sup>*</sup></label>
    <input class="form__input <?= $userNameCssClass; ?>" type="text" name="name" id="name" value="<?= $userName; ?>" placeholder="Введите имя">
    <p class="form__message"><?= $userNameErrorMessage; ?></p>
  </div>

  <div class="form__row form__row--controls">
    <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
    <input class="button" type="submit" name="" value="Зарегистрироваться">
  </div>
</form>
