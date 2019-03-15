<?php

require_once("vendor/autoload.php");
require_once("db-api.php");
require_once("email-notify-class.php");

$db = new DbApi();
$notifier = new EmailNotifyClass();

$today = date("Y-m-d");
$tasksToday = getAdaptedTasks($db->getTasksOfAllUsers($today));

$userIds = getUniqueUserIdsOfTasks($tasksToday);
$users = $db->getUsersDataByIds($userIds);

foreach($users as $user) {
    $userId = $user["id"] ?? null;

    $userTasks = array_filter($tasksToday, function($task) use($userId) {
        if (!isset($task["authorUserId"]) || !isset($task["isDone"])) {
            return false;
        };

        $result = ($task["authorUserId"] === $userId) && !$task["isDone"];
        return $result;
    });

    $notifier->sendUpcomingNotification($user, $userTasks);
};
