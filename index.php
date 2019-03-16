<?php

include_once("functions.php");
include_once("db-api.php");
include_once('session.php');
include_once('pages/search-task-form.php');

const WEBPAGE_TITLE = "Дела в порядке";

$session = new Session();
$db = new DbApi($session->getUserId());
$searchForm = new SearchTaskForm();

$db->setTaskIsDone(getToggledTaskState());

if (isset($_GET["show_completed"])) {
    $session->setProp("showCompleted", (integer)$_GET["show_completed"]);
};

if (isset($_GET["id"])) {
    $session->setProp("projectId", (integer)$_GET["id"]);
};

if (isset($_GET["filter"])) {
    $session->setProp("filterId", (integer)$_GET["filter"]);
};

if (empty($_GET) || $searchForm->isMethodPost()) {
    $session->setProp("showCompleted");
    $session->setProp("filterId");
    $session->setProp("projectId");
};

if ($searchForm->isMethodPost() && $searchForm->isValid()) {
    $searchText = $searchForm->getValuePublic("title");
    $session->setProp("searchText", $searchText);
};

$tasks = getAdaptedTasks($db->getTasks());
$layoutData = [
    "data" => [
        "pageTitle" => WEBPAGE_TITLE,
        "user" => [
            "name" => $session->getUserName(),
            "id" => $session->getUserId(),
        ],
        "projects" => getAdaptedProjects($db->getProjects()),

        "tasks" => $tasks,
        "filteredTasks" => $session->getProp("searchText") !== null ?
            getAdaptedTasks($db->searchTasks($session->getProp("searchText"))) :
            getAdaptedTasks($db->getTasks(), $session->getProp("filterId")),

        "projectId" => $session->getProp("projectId"),
        "showCompleteTasks" => $session->getProp("showCompleted"),
        "tasksFilterId" => $session->getProp("filterId"),

        "postTitle" => $session->setProp("postTitle"),
        ]
];

$layoutData["data"]["components"] = [
    "main" => include_template("index.php", $layoutData["data"]),
];

echo include_template("layout.php", $layoutData);
