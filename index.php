<?php
    include_once("functions.php");
    include_once("db-api.php");
    include_once('session.php');

    const SHOW_COMPLETE_TASKS_CSS_ATTRIBUTE = "checked";
    const WEBPAGE_TITLE = "Дела в порядке";

    $session = new Session();
    $db = new DbApi($session->getUserId());

    $db->setTaskIsDone(getToggledTaskState());

    if (empty($_GET)) {
        $session->setCustomProp("showCompleted");
        $session->setCustomProp("filter");
        $session->setCustomProp("projectId");
    };

    if (isset($_GET["show_completed"])) {
        $showCompleteTasks = (integer)($_GET["show_completed"]);
        $session->setCustomProp("showCompleted", $showCompleteTasks);
    } else {
        $showCompleteTasks = (integer)$session->getCustomProp("showCompleted");
    };
    
    if (isset($_GET["filter"])) {
        $taskFilterId = (integer)($_GET["filter"]);
        $session->setCustomProp("filter", $taskFilterId);
    } else {
        $taskFilterId = (integer)$session->getCustomProp("filter");
    };

    if (isset($_GET["id"])) {
        $projectId = (integer)$_GET["id"];
        $session->setCustomProp("projectId", $projectId);
    } else {
        $projectId = (integer)$session->getCustomProp("projectId");
    };
    
    $layoutData = [
        "data" => [
            "pageTitle" => WEBPAGE_TITLE,
            "showCompleteTasks" => $showCompleteTasks,
            "user" => $session->getUserData(),
            "tasksFilterId" => $taskFilterId,
            "projectId" => $projectId,
            "tasks" => getAdaptedTasks($db->getTasks()),
            ]
    ];

    $layoutData["data"]["filteredTasks"] = getAdaptedTasks($db->getTasks(), $taskFilterId);
    $layoutData["data"]["projects"] = getAdaptedProjects($db->getProjects());

    $layoutData["data"]["components"] = [
        "main" => include_template("index.php", $layoutData["data"]),
    ];

    echo include_template("layout.php", $layoutData);
