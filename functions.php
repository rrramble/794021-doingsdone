<?php
const SERVER_TIMEZONE = "Asia/Oral";


/**
 * @param string $dateReadable
 *
 * @return string
 */
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


/**
 * @param string $name
 * @param array $data
 *
 * @return string
 */
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

    return ob_get_clean();
}


/**
 * @param integer $projectId
 * @param integer $userId
 * @param array $tasks
 *
 * @return integer
 */
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


/**
 * @param string $dateToCheck
 *
 * @return boolean
 */
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


/**
 * @param integer $recent
 * @param integer $elder
 *
 * @return integer
 */
function getHoursDiff($recent, $elder)
{
    $SECONDS_IN_HOUR = 3600;

    $hoursDiff = ($recent - $elder) / $SECONDS_IN_HOUR;
    return floor($hoursDiff);
};


/**
 * @param array $dbTasks
 * @param integer $filter
 *
 * @return array
 */
function getAdaptedTasks($dbTasks, $filter = 0)
{
    $TASK_STATE_DONE = 1;
    date_default_timezone_set(SERVER_TIMEZONE);

    $results = [];
    if (!$dbTasks) {
        return $results;
    };

    $todayInt = (integer)date("Ymd");

    foreach($dbTasks as $dbTask) {
        $item['isDone'] = (integer)$dbTask['state_id'] === (integer)$TASK_STATE_DONE;
        $item['id'] = (integer)$dbTask['id'];
        $item['title'] = $dbTask['title'];
        $item['projectId'] = (integer)$dbTask['project_id'];
        $item['authorUserId'] = (integer)$dbTask['author_user_id'];
        $item['filePath'] = $dbTask['file_path'];

        $dateTime = date_create_from_format("Y-m-d H:i:s", $dbTask['due_date']);
        $item['dueDate'] = $dateTime ? date_format($dateTime, "Y-m-d") : "";

        if ($filter === 1 && !isTodayIsoDate($item['dueDate'])) {
            continue;
        };
        if ($filter === 2 && !isTomorrowIsoDate($item['dueDate'])) {
            continue;
        };

        $dueDateInt = getIntFromIsoDateTime($dbTask['due_date']);
        if ($filter === 3 && (!$item["dueDate"] || isTodayOrFutureIsoDate($item['dueDate']) || $item["isDone"])) {
            continue;
        };

        array_push($results, $item);
    };

    return $results;
};


/**
 * @param array $dbProjects
 *
 * @return array
 */
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

/**
 * @param string $isoDateString
 *
 * @return integer
 */
function getIntFromIsoDateTime($isoDateString)
{
    if (!$isoDateString) {
        return null;
    };

    list($year, $month, $day) = sscanf($isoDateString, "%d-%d-%d");
    if (!$year || !$month || !$day) {
        return null;
    };

    return (integer)$year * 10000 + (integer)$month * 100 + (integer)$day;
}


/**
 * @return array
 */
function getToggledTaskState()
{//!
    if (!isset($_GET["task_id"]) || !isset($_GET["check"])) {
        return null;
    };

    return [
        "id" => (integer)$_GET["task_id"],
        "isDone" => (integer)$_GET["check"],
    ];
}


/**
 * @param integer $projectId
 *
 * @return string
 */
function getProjectUrl($projectId)
{ //!
    return '/index.php?id=' . $projectId;
}


/**
 * @param array $tasks
 * @param integer $projectId
 *
 * @return array
 */
function getTasksFilteredByProjectId($tasks, $projectId)
{
    $results = array_filter($tasks, function($task) {
        return $task['id'] === $projectId;
    });
    return $results;
}


/**
 * @param string $title
 * @param array $list
 *
 * @return boolean
 */
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


/**
 * @param string $isoDate
 *
 * @return boolean
 */
function isTodayIsoDate($isoDate)
{
    date_default_timezone_set(SERVER_TIMEZONE);
    if (!$isoDate) {
        return false;
    };

    $isoToday = date("Y-m-d");
    return $isoDate === $isoToday;
}


/**
 * @param string $isoDate
 *
 * @return boolean
 */
function isTodayOrFutureIsoDate($isoDate)
{
    date_default_timezone_set(SERVER_TIMEZONE);
    if (!$isoDate) {
        return false;
    };
    $dateTime = date_create_from_format("Y-m-d", $isoDate);
    $dateTimeToday = date_create_from_format("Y-m-d", date("Y-m-d"));

    if (!$dateTime) {
        return false;
    };
    return $dateTime >= $dateTimeToday;
}


/**
 * @param string $isoDate
 *
 * @return boolean
 */
function isTomorrowIsoDate($isoDate)
{
    date_default_timezone_set(SERVER_TIMEZONE);
    if (!$isoDate) {
        return false;
    };

    $isoTomorrow = date("Y-m-d", strtotime("+1 day"));
    return $isoDate === $isoTomorrow;
}
