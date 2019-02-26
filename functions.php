<?php

$TASK_STATE_DONE = 1;

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

function getTasksCategoryCount($tasks, $categoryName, $categories)
{
    $count = 0;
    foreach($tasks as $task) {
        $thisTaskCategoryName = $categories[$task["categoryId"]];
        if ($thisTaskCategoryName === $categoryName) {
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
}

function getHoursDiff($recent, $elder)
{
    $SECONDS_IN_HOUR = 3600;

    $hoursDiff = ($recent - $elder) / $SECONDS_IN_HOUR;
    return floor($hoursDiff);
}

function getAdaptedTasks($dbTasks)
{
    $tasks = [];

    if (!$dbTasks) {
        return $task;
    }

    foreach($dbTasks as $dbTask) {
        $item = array();
        $item['title'] = $dbTask['title'];
        $item['dueDate'] = $dbTask['due_date'];
        $item['categoryId'] = $dbTask['project_id'];
        $item['isDone'] = $dbTask['state_id'] === $TASK_STATE_DONE;
        array_push($tasks, $item);
    };

    return $tasks;
}
