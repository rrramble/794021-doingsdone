<?php
include_once('../functions.php');
include_once('../db-api.php');
include_once('auth-form.php');
include_once('../session.php');

const WEBPAGE_TITLE = 'Авторизация на сайте';
const PAGE_IF_ALREADY_LOGGED_IN = "/index.php";
$SCRIPT_NAME_IF_SUCCESS = '/index.php';
$SCRIPT_NAME_IF_FAILURE = 'auth.php';
$FormMessage = [
    'OVERALL_ERROR' => 'Пожалуйста, исправьте ошибки в форме',
    'AUTH_ERROR' => 'Вы ввели неверный email/пароль',
    'EMAIL_IS_EMPTY' => 'Укажите электронную почту',
    'EMAIL_IS_NOT_VALID' => 'Неверный формат электронной почты',
];

$session = new Session();
if ($session->getUserId()) {
    header("Location: " . PAGE_IF_ALREADY_LOGGED_IN);
    die();
};

$form = new AuthForm();

$layoutData = [
    'data' => [
        'pageTitle' => WEBPAGE_TITLE,
        'isShowTemplateEvenUnauthorised' => true,
        'emailErrorMessage' => '',
    ],
];

if ($form->isMethodPost()) {
    $email = $form->getValuePublic('email');
    $password = $form->getValuePublic('password');

    $db = new DbApi();
    if ($form->isValid() && $db->isValidUserCredential($email, $password)) {
        $userData = $db->getUserDataByEmail($email);
        $session->setUserData([
            "userName" => $userData["userName"] ?? null,
            "id" => $userData["id"] ?? 0,
        ]);

        header('Location: ' . $SCRIPT_NAME_IF_SUCCESS);
        die();
    };

    $layoutData['data']['postEmail'] = $email;

    if (mb_strlen($email) <= 0) {
        $layoutData['data']['emailErrorMessage'] = $FormMessage['EMAIL_IS_EMPTY'];
    } elseif (!$form->isFieldValid('email')) {
        $layoutData['data']['emailErrorMessage'] = $FormMessage['EMAIL_IS_NOT_VALID'];
    } else {
        $layoutData['data']['formErrorMessage'] = $FormMessage['AUTH_ERROR'];
    };
};

$layoutData["data"]["components"] = [
    "main" => include_template("auth.php", $layoutData),
];

echo include_template('layout.php', $layoutData);
