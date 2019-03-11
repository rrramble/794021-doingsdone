<?php
include_once('../functions.php');
include_once('../session.php');

const WEBPAGE_TITLE = "Регистрация пользователя";
const PAGE_IF_ALREADY_LOGGED_IN = "/index.php";

$session = new Session();
if ($session->getUserId()) {
    header("Location: " . PAGE_IF_ALREADY_LOGGED_IN);
    die();
};

$layoutData = [
    'data' => [
        'pageTitle' => WEBPAGE_TITLE,
    ],
];

$layoutData["data"]["components"] = [
    "main" => include_template("guest.php", $layoutData["data"]),
];

echo include_template("layout.php", $layoutData);
