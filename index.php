<?php
    include_once("functions.php");
    include_once("db-api.php");
    include_once('session.php');

    const SHOW_COMPLETE_TASKS_CSS_ATTRIBUTE = "checked";
    const WEBPAGE_TITLE = "Дела в порядке";

    $session = new Session();
    $db = new DbApi($session->getUserId());

    $db->setTaskIsDone(getToggledTaskState());

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

    if (isset($_GET['id'])) {
        $projectId = (integer)$_GET["id"];
        $session->setCustomProp("showCompleted", $projectId);
    } else {
        $projectId = (integer)$session->getCustomProp("id");
    };
    
    $layoutData = [
        "data" => [
            "pageTitle" => WEBPAGE_TITLE,
            "showCompleteTasks" => $showCompleteTasks,
            "user" => $session->getUserData(),
            "tasksFilterId" => $taskFilterId,
            "projectId" => $projectId,
            ]
    ];

    $layoutData["data"]["tasks"] = isset($layoutData["data"]["user"]["id"]) ?
        getAdaptedTasks($db->getTasks($layoutData["data"]["user"]["id"]), $taskFilterId) :
        NULL;

    $layoutData["data"]["projects"] = isset($layoutData["data"]["user"]["id"]) ?
        getAdaptedProjects($db->getProjects()) :
        NULL;

    $layoutData["data"]["components"] = [
        "main" => include_template("index.php", $layoutData["data"]),
    ];

    echo include_template("layout.php", $layoutData);
