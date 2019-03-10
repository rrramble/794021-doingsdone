<?php
    include_once("functions.php");
    include_once("db-api.php");
    include_once('session.php');

    const SHOW_COMPLETE_TASKS_CSS_ATTRIBUTE = "checked";
    const WEBPAGE_TITLE = "Дела в порядке";

    $db = new DbApi();
    $session = new Session();

    $db->setTaskIsDone(getToggledTaskState());
    
    $layoutData = [
        "data" => [
            "pageTitle" => WEBPAGE_TITLE,
            "showCompleteTasks" => (integer)($_GET["show_completed"] ?? 0),
            "user" => $session->getUserData(),
        ]
    ];

    $layoutData["data"]["tasks"] = isset($layoutData["data"]["user"]["id"]) ?
        getAdaptedTasks($db->getTasks($layoutData["data"]["user"]["id"])) :
        NULL;

    $layoutData["data"]["projects"] = isset($layoutData["data"]["user"]["id"]) ?
        getAdaptedProjects($db->getProjects()) :
        NULL;

    $layoutData["data"]["currentProjectId"] = isset($_GET['id']) ?
        $_GET['id'] :
        NULL;

    $layoutData["data"]["components"] = [
        "main" => include_template("index.php", $layoutData["data"]),
    ];

    echo include_template("layout.php", $layoutData);
