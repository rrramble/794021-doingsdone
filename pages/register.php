<?php
include_once('../functions.php');
include_once('../db-api.php');
include_once('register-form.php');

$WEBPAGE_TITLE = 'Регистрация пользователя';
$SCRIPT_NAME_IF_SUCCESS = '/index.php';
$SCRIPT_NAME_IF_FAILURE = 'register.php';
$FormMessage = [
    'OVERALL_ERROR' => 'Пожалуйста, исправьте ошибки в форме',
    'NO_TITLE_ERROR' => 'Нужно указать название',
    'TITLE_ALREADY_EXISTS' => 'Название уже существует',
    'DATE_MUST_BE_IN_FUTURE' => 'Дата должна быть в будущем'
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
