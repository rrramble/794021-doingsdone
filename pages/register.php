<?php
include_once('../functions.php');
include_once('../db-api.php');
include_once('register-form.php');

$WEBPAGE_TITLE = 'Регистрация пользователя';
$SCRIPT_NAME_IF_SUCCESS = '/index.php';
$SCRIPT_NAME_IF_FAILURE = 'register.php';
$FormMessage = [
    'OVERALL_ERROR' => 'Пожалуйста, исправьте ошибки в форме',
    'EMAIL_IS_EMPTY' => 'Указать электронную почту',
    'EMAIL_IS_WRONG' => 'Неверный формат электронной почты',
    'EMAIL_ALREADY_EXISTS' => 'Пользователь с такой электронной почтой уже зарегистрирован',
    'USERNAME_IS_WRONG' => 'Указать имя пользователя'
];

$layoutData = [
    'data' => [
        'pageTitle' => $WEBPAGE_TITLE,
        'isShowTemplateEvenUnauthorised' => true,
    ],
];

$db = new DbApi();
$form = new RegisterForm();

if ($form->isMethodPost()) {
    $postEmail = $form->getValuePublic('email');

    if (isOverallFormValid($form, $db)) {
        $db->addUser($form->getFieldsPublic());
        header('Location: ' . $SCRIPT_NAME_IF_SUCCESS);
        die();
    };

    $layoutData['data']['postEmail'] = $postEmail;
    $layoutData['data']['postUserName'] = $form->getValuePublic('userName');
    $layoutData['data']['emailErrorMessage'] = '';
    $layoutData['data']['userNameErrorMessage'] = '';

    if (mb_strlen($postEmail) <= 0) {
        $layoutData['data']['emailErrorMessage'] = $FormMessage['EMAIL_IS_EMPTY'];
    } elseif (!$form->isFieldValid('email')) {
        $layoutData['data']['emailErrorMessage'] = $FormMessage['EMAIL_IS_WRONG'];
    } elseif ($db->isUserEmailExist($postEmail)) {
        $layoutData['data']['emailErrorMessage'] = $FormMessage['EMAIL_ALREADY_EXISTS'];
    };

    $layoutData['data']['userNameErrorMessage'] = !$form->isFieldValid('userName') ?
        $FormMessage['USERNAME_IS_WRONG'] :
        '';
};

$layoutData['data']['components']['main'] = include_template('register.php', $layoutData);

echo include_template('layout.php', $layoutData);
die();


function isOverallFormValid($form, $db)
{
    return
        $form->isValid() &&
        !$db->isUserEmailExist($form->getValuePublic('email'))
    ;
}
