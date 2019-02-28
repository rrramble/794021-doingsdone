<?php

function include_template($name, $data)
{
    $name = 'templates/' . $name;
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
        if ($task['projectId'] === $projectId && $task['authorUserId'] === $userId) {
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

function getAdaptedTasks($dbTasks)
{
    $TASK_STATE_DONE = 1;
    $results = [];

    if (!$dbTasks) {
        return $results;
    }

    foreach($dbTasks as $dbTask) {
        $item['id'] = (integer)$dbTask['id'];
        $item['title'] = $dbTask['title'];
        $item['dueDate'] = $dbTask['due_date'];
        $item['projectId'] = (integer)$dbTask['project_id'];
        $item['isDone'] = $dbTask['state_id'] === $TASK_STATE_DONE;
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

function getProjectUrl($projectId)
{
    return '/index.php?id=' . $projectId;
}
