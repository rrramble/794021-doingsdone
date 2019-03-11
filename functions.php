<?php
const SERVER_TIMEZONE = "Asia/Oral";

function convertDateReadableToHtmlFormInput($dateReadable)
{
    if (mb_strlen($dateReadable <= 0)) {
        return null;
    };
    $dateDMY = date_create_from_format('d.m.Y', $dateReadable);
    $dateYMD = date_create_from_format('Y-m-d', $dateReadable);

    if (!$dateDMY && !$dateYMD) {
        throw new Exception("Is not a date: " . $dateReadable);
    };
    return date_format($dateDMY ? $dateDMY : $dateYMD, 'Y-m-d');
}

function include_template($name, $data)
{
    $name = __DIR__ . '/templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function getTasksCount($projectId, $userId, $tasks)
{
    $count = 0;
    foreach($tasks as $task) {
        if (
            (string)$task['projectId'] === (string)$projectId &&
            (string)$task['authorUserId'] === (string)$userId
        ) {
            $count++;
        };
    };
    return $count;
}

function isDeadlineNear($dateToCheck)
{
    $HOURS_DEADLINE = 24;
    if ($dateToCheck === NULL) {
        return false;
    }

    $now = strtotime("now");
    $dueDate = strtotime($dateToCheck);
    return getHoursDiff($dueDate, $now) <= $HOURS_DEADLINE;
};

function getHoursDiff($recent, $elder)
{
    $SECONDS_IN_HOUR = 3600;

    $hoursDiff = ($recent - $elder) / $SECONDS_IN_HOUR;
    return floor($hoursDiff);
};

function getAdaptedTasks($dbTasks, $filter = 0)
{
    $TASK_STATE_DONE = 1;
    $results = [];

    if (!$dbTasks) {
        return $results;
    };
    date_default_timezone_set(SERVER_TIMEZONE);
    $todayInt = (integer)date("Ymd");

    foreach($dbTasks as $dbTask) {
        $item['isDone'] = (integer)$dbTask['state_id'] === (integer)$TASK_STATE_DONE;

        list($year, $month, $day) = sscanf($dbTask['due_date'], "%d-%d-%d");
        $dueDateInt = $year * 10000 + $month * 100 + $day;
        if ($dueDateInt === 0) {
            $dueDateInt = null;
            $item['dueDate'] = "";
        } else {
            $item['dueDate'] = sprintf("%04d-%02d-%02d", $year, $month, $day);
        };

        if ($filter === 1 && $dueDateInt !== $todayInt) {
            continue;
        };
        if ($filter === 2 && $dueDateInt !== $todayInt + 1) {
            continue;
        };
        if ($filter === 3 && ($dueDateInt === null || $dueDateInt >= $todayInt || $item["isDone"])) {
            continue;
        };

        $item['id'] = (integer)$dbTask['id'];
        $item['title'] = $dbTask['title'];
        $item['projectId'] = (integer)$dbTask['project_id'];
        $item['authorUserId'] = (integer)$dbTask['author_user_id'];
        array_push($results, $item);
    };

    return $results;
};

function getAdaptedProjects($dbProjects)
{
    $results = [];
    if (!$dbProjects) {
        return $results;
    }

    foreach($dbProjects as $dbProject) {
        $item['id'] = (integer)$dbProject['id'];
        $item['title'] = $dbProject['title'];
        $item['authorUserId'] = (integer)$dbProject['author_user_id'];
        array_push($results, $item);
    };

    return $results;
};

function getToggledTaskState()
{
    if (!isset($_GET["task_id"]) || !isset($_GET["check"])) {
        return null;
    };

    return [
        "id" => (integer)$_GET["task_id"],
        "isDone" => (integer)$_GET["check"],
    ];
}

function getProjectUrl($projectId)
{
    return '/index.php?id=' . $projectId;
}

function getTasksFilteredByProjectId($tasks, $projectId)
{
    $results = array_filter($tasks, function($task) {
        return $task['id'] === $projectId;
    });
    return $results;
}

/**
 * param integer $projectId
 * param mixed $projects
 * return boolean
 */
function isProjectIdExists($projectId, $projects)
{
    $result = false;
    foreach($projects as $project) {
        if ($projectId === $project['id']) {
            $result = true;
            break;
        };
    };
    return $result;
}

function isTaskExists($taskName, $tasks)
{
    $result = false;
    $taskName = mb_strtoupper($taskName);

    foreach($tasks as $task) {
        if ($taskName === mb_strtoupper($task['title'])) {
            $result = true;
            break;
        };
    };
    return $result;
}

function isTitleExist($title, $list)
{
    $result = false;
    $title = mb_strtoupper($title);

    foreach($list as $item) {
        if ($title === mb_strtoupper($item['title'])) {
            $result = true;
            break;
        };
    };
    return $result;
}