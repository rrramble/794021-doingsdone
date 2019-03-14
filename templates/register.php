<?php
if (!isset($data)) {
    header("Location: /");
    die();
};

$email = $data['postEmail'] ?? '';
$emailErrorMessage = $data['emailErrorMessage'] ?? '';
$emailErrorCssClass = $emailErrorMessage ? 'form__input--error' : '';

$userName = $data['postUserName'] ?? '';
$userNameErrorMessage = $data['userNameErrorMessage'] ?? '';
$userNameCssClass = $userNameErrorMessage ? 'form__input--error' : '';

$passwordErrorMessage = $data['passwordErrorMessage'] ?? '';
$userNameCssClass = $userNameErrorMessage ? 'form__input--error' : '';
?>
<h2 class="content__main-heading">Регистрация аккаунта</h2>

<form class="form" action="/pages/register.php" method="post">
  <div class="form__row">
    <label class="form__label" for="email">E-mail <sup>*</sup></label>

    <input
      class="form__input <?= strip_tags($emailErrorCssClass); ?>"
      type="text" name="email" id="email"
      value="<?= strip_tags($email); ?>"
      placeholder="Введите e-mail"
    >

    <p class="form__message"><?= strip_tags($emailErrorMessage); ?></p>
  </div>

  <div class="form__row">
    <label class="form__label" for="password">Пароль <sup>*</sup></label>
    <input class="form__input" type="password" name="password" id="password" value="" placeholder="Введите пароль">
    <p class="form__message">
      <?= strip_tags($passwordErrorMessage); ?>
    </p>
  </div>

  <div class="form__row">
    <label class="form__label" for="name">Имя <sup>*</sup></label>

    <input
      class="form__input <?= strip_tags($userNameCssClass); ?>"
      type="text" name="name" id="name"
      value="<?= strip_tags($userName); ?>"
      placeholder="Введите имя"
    >

    <p class="form__message">
      <?= strip_tags($userNameErrorMessage); ?>
    </p>
  </div>

  <div class="form__row form__row--controls">
    <?php if ($emailErrorMessage || $userNameErrorMessage || $passwordErrorMessage): ?>
      <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
    <?php endif; ?>
    <input class="button" type="submit" name="" value="Зарегистрироваться">
  </div>
</form>
