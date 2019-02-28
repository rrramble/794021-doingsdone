<?php
    include_once("functions.php");

    $show_complete_tasks_attribute = "checked";

    $DbSettings = [
        'HOST' => '127.0.0.1',
        'USERNAME' => 'root',
        'PASSWORD' => '',
        'DB_NAME' => '794021_doingsdone',
        'ENCODING' => 'utf8'
    ];

    $db = mysqli_connect(
        $DbSettings['HOST'],
        $DbSettings['USERNAME'],
        $DbSettings['PASSWORD'],
        $DbSettings['DB_NAME']
    );

    if (!$db) {
        throw new Exception(
            'Error connecting to Database "' .
            $DbSettings['DB_NAME'] .
            '"' .
            mysqli_connect_error()
        );
    }

    mysqli_set_charset($db, $DbSettings['ENCODING']);

    $currentUserId = 1;

    $queryProjects  = "SELECT title FROM projects";
    $result = mysqli_query($db, $queryProjects);
    if (!$result) {
        throw new Exception(mysqli_error($db));
    };
    $project_categories = adaptDbResult($result, $getAdaptedProjectNames);


    $queryTasks  = "SELECT * FROM tasks WHERE author_user_id = '$currentUserId'";
    $result = mysqli_query($db, $queryTasks);
    if (!$result) {
        throw new Exception(mysqli_error($db));
    };
    $tasks = adaptDbResult($result, $getAdaptedTasks);
    $show_complete_tasks = rand(0, 1);

    $pageTitle = "Дела в порядке";

    $layoutData = [
        "data" => [
            "pageTitle" => $pageTitle,
            "show_complete_tasks_attribute" => $show_complete_tasks_attribute,
            "project_categories" => $project_categories,
            "tasks" => $tasks,
            "show_complete_tasks" => $show_complete_tasks,
        ]
    ];

    $layoutData["data"]["components"] = [
        "main" => include_template("index.php", $layoutData["data"]),
    ];

    echo include_template("layout.php", $layoutData);
