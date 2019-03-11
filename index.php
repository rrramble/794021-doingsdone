<?php
    include_once("functions.php");
    include_once("db-api.php");
    include_once('session.php');

    const SHOW_COMPLETE_TASKS_CSS_ATTRIBUTE = "checked";
    const WEBPAGE_TITLE = "Дела в порядке";

    $db = new DbApi();
    $session = new Session();

    $db->setTaskIsDone(getToggledTaskState());
    if (isset($_GET["show_completed"])) {
        $showCompleteTasks = (integer)($_GET["show_completed"]);
        $session->setCustomProp("showCompleted", $showCompleteTasks);
    } else {
        $showCompleteTasks = (integer)$session->getCustomProp("showCompleted");
    };
    
    if (isset($_GET["filter"])) {
        $taskFilter = (integer)($_GET["filter"]);
        $session->setCustomProp("filter", $filterTasks);
    } else {
        $taskFilter = (integer)$session->getCustomProp("filter");
    };

    
    $layoutData = [
        "data" => [
            "pageTitle" => WEBPAGE_TITLE,
            "showCompleteTasks" => $showCompleteTasks,
            "user" => $session->getUserData(),
            "tasksFilter" => $taskFilter,
        ]
    ];

    $layoutData["data"]["tasks"] = isset($layoutData["data"]["user"]["id"]) ?
        getAdaptedTasks($db->getTasks($layoutData["data"]["user"]["id"]), $taskFilter) :
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
