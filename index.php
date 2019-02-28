<?php
    include_once("functions.php");
    include_once("db-api.php");

    $show_complete_tasks_attribute = "checked";
    $WEBPAGE_TITLE = "Дела в порядке";
    $show_complete_tasks = rand(0, 1);

    $db = new DbApi();

    $currentUserId = 1;
    if (isset($_GET['id'])) {
        $currentProjectId = $_GET['id'];
    }

    if (!$db->isProjectIdExists($currentProjectId)) {
        header("HTTP/1.0 404 Not Found");
        die();
    }

    $tasks = getAdaptedTasks($db->getTasks($currentUserId, $currentProjectId));
    $projects = getAdaptedProjects($db->getProjects());

    $layoutData = [
        "data" => [
            "pageTitle" => $WEBPAGE_TITLE,
            "show_complete_tasks_attribute" => $show_complete_tasks_attribute,
            "projects" => $projects,
            "tasks" => $tasks,
            "show_complete_tasks" => $show_complete_tasks,
            "userId" => $currentUserId
        ]
    ];

    $layoutData["data"]["components"] = [
        "main" => include_template("index.php", $layoutData["data"]),
    ];

    echo include_template("layout.php", $layoutData);
