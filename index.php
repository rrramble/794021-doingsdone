<?php

include_once("functions.php");
include_once("db-api.php");
include_once('session.php');
include_once('pages/search-task-form.php');

const SHOW_COMPLETE_TASKS_CSS_ATTRIBUTE = "checked";
const WEBPAGE_TITLE = "Дела в порядке";

$session = new Session();
$db = new DbApi($session->getUserId());
$searchForm = new SearchTaskForm();

$db->setTaskIsDone(getToggledTaskState());

if (isset($_GET["show_completed"])) {
    $showCompleteTasks = (integer)($_GET["show_completed"]);
    $session->setCustomProp("showCompleted", $showCompleteTasks);
} else {
    $showCompleteTasks = (integer)$session->getCustomProp("showCompleted");
};

if (isset($_GET["id"])) {
    $projectId = (integer)$_GET["id"];
    $session->setCustomProp("projectId", $projectId);
} else {
    $projectId = (integer)$session->getCustomProp("projectId");
};

if (isset($_GET["filter"])) {
    $taskFilterId = (integer)($_GET["filter"]);
    $session->setCustomProp("filter", $taskFilterId);
} else {
    $taskFilterId = (integer)$session->getCustomProp("filter");
};

if (empty($_GET) || $searchForm->isMethodPost()) {
    $session->setCustomProp("showCompleted");
    $session->setCustomProp("filter");
    $session->setCustomProp("projectId");
    $showCompleteTasks = null;
    $projectId = null;
    $taskFilterId = null;
};

$postTitle = null;
if ($searchForm->isMethodPost() && $searchForm->isValid()) {
    $postTitle = $searchForm->getValuePublic("title");
};

$tasks = getAdaptedTasks($db->getTasks());
$layoutData = [
    "data" => [
        "pageTitle" => WEBPAGE_TITLE,
        "user" => $session->getUserData(),
        "tasks" => $tasks,
        "projects" => getAdaptedProjects($db->getProjects()),

        "projectId" => $projectId,
        "showCompleteTasks" => $showCompleteTasks,
        "tasksFilterId" => $taskFilterId,

        "postTitle" => $postTitle ?? null,
        ]
];

$layoutData["data"]["filteredTasks"] = $postTitle ?
    getAdaptedTasks($db->searchTasks($postTitle)) :
    getAdaptedTasks($db->getTasks(), $taskFilterId);

$layoutData["data"]["components"] = [
    "main" => include_template("index.php", $layoutData["data"]),
];

echo include_template("layout.php", $layoutData);
