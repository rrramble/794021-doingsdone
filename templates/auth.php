<?php
if (!isset($data)) {
    header("Location: /");
    die();
};

    $email = $data['postEmail'] ?? '';
    $emailErrorMessage = $data['emailErrorMessage'] ?? '';
    $emailErrorCssClass = $emailErrorMessage ? 'form__input--error' : '';
    $formErrorMessage = $data['formErrorMessage'] ?? '';
?>
<h2 class="content__main-heading">Вход на сайт</h2>

<form class="form" action="auth.php" method="post">
  <div class="form__row">
    <label class="form__label" for="email">E-mail <sup>*</sup></label>
    <input
        class="form__input <?= $emailErrorCssClass; ?>"
        type="text" name="email" id="email"
        value="<?= $email; ?>"
        placeholder="Введите e-mail">
    <p class="form__message"><?= $emailErrorMessage; ?></p>
  </div>

  <div class="form__row">
    <label class="form__label" for="password">Пароль <sup>*</sup></label>
    <input
        class="form__input" type="password" name="password" id="password"
        value=""
        placeholder="Введите пароль">
  </div>

  <div class="form__row form__row--controls">
    <input class="button" type="submit" name="" value="Войти">
  </div>

  <?php if ($formErrorMessage):?>
    <p class="error-message"><?= $formErrorMessage; ?></p>
  <?php endif; ?>

</form>
