<?php
include_once('../functions.php');
include_once('../db-api.php');
include_once('auth-form.php');

$WEBPAGE_TITLE = 'Авторизация на сайте';
$SCRIPT_NAME_IF_SUCCESS = '/index.php';
$SCRIPT_NAME_IF_FAILURE = 'auth.php';
$FormMessage = [
    'AUTH_ERROR' => 'Неверный пароль или электронная почта',
    'EMAIL_IS_EMPTY' => 'Укажите электронную почту',
    'EMAIL_IS_NOT_VALID' => 'Неверный формат электронной почты',
];

$layoutData = [
    'data' => [
        'pageTitle' => $WEBPAGE_TITLE,
    ],
];

$db = new DbApi();
$form = new AuthForm();

if ($form->isMethodPost()) {

    if (isOverallFormValid($form, $db)) {
        header('Location: ' . $SCRIPT_NAME_IF_SUCCESS);
        die();
    };

    $layoutData['data']['postEmail'] = $form->getValuePublic('email');
    $layoutData['data']['emailErrorMessage'] = '';

    if (mb_strlen($layoutData['data']['postEmail']) <= 0) {
        $layoutData['data']['emailErrorMessage'] = $FormMessage['EMAIL_IS_EMPTY'];
    } elseif (!$form->isFieldValid('email')) {
        $layoutData['data']['emailErrorMessage'] = $FormMessage['EMAIL_IS_NOT_VALID'];
    } else {
        $layoutData['data']['formErrorMessage'] = $FormMessage['AUTH_ERROR'];
    };
};

echo include_template('auth.php', $layoutData);
die();


function isOverallFormValid($form, $db)
{
    return
        $form->isValid() &&
        $db->isValidUserCredential(
            $form->getValuePublic('email'),
            $form->getValuePublic('password')
        );
}
