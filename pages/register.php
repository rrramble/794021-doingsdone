<?php
include_once('../functions.php');
include_once('../db-api.php');
include_once('register-form.php');

$WEBPAGE_TITLE = 'Регистрация пользователя';
$SCRIPT_NAME_IF_SUCCESS = '/index.php';
$SCRIPT_NAME_IF_FAILURE = 'register.php';
$FormMessage = [
    'OVERALL_ERROR' => 'Пожалуйста, исправьте ошибки в форме',
    'EMAIL_IS_EMPTY' => 'Нужно указать электронную почту',
    'EMAIL_IS_WRONG' => 'Неверный формат электронной почты',
    'EMAIL_ALREADY_EXISTS' => 'Пользователь с такой электронной почтой уже зарегистрирован',
];

$layoutData = [
    'data' => [
        'pageTitle' => $WEBPAGE_TITLE,
    ],
];

$db = new DbApi();
$form = new RegisterForm();

if ($form->isMethodPost()) {
    if ($form->isValid()) {
        $db->saveUser($form->getFieldsPublic());
        header('Location: ' . $SCRIPT_NAME_IF_SUCCESS);
        die();
    };
    $layoutData['data']['postEmail'] = $form->getValuePublic('email');
    $layoutData['data']['postUserName'] = $form->getValuePublic('userName');
};

echo include_template('register.php', $layoutData);
