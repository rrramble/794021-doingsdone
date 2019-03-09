<?php
include_once('../session.php');

const PAGE_AFTER_LOGOUT = "/index.php";

$session = new Session();
$session->logout();

header("Location:" . PAGE_AFTER_LOGOUT);
