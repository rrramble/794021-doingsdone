<?php
    include_once("functions.php");

    $show_complete_tasks_attribute = "checked";
    $project_categories = [
        "Входящие",
        "Учеба",
        "Работа",
        "Домашние дела",
        "Авто"
    ];

    $DbSettings = [
        'HOST' => '127.0.0.1',
        'USERNAME' => 'root',
        'PASSWORD' => '',
        'DB_NAME' => '794021_doingsdone',
        'ENCODING' => 'utf-8'
    ];

    $db = mysqli_connect(
        $DbSettings['HOST'],
        $DbSettings['USERNAME'],
        $DbSettings['PASSWORD'],
        $DbSettings['DB_NANE']
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

    $tasks = [
        [
            "title" => "Собеседование в IT компании",
            "dueDate" => "01.12.2019",
            "categoryId" => 2,
            "isDone" => FALSE,
        ],
        [
            "title" => "Выполнить тестовое задание",
            "dueDate" => "25.12.2019",
            "categoryId" => 2,
            "isDone" => FALSE,
        ],
        [
            "title" => "Сделать задание первого раздела",
            "dueDate" => "21.12.2019",
            "categoryId" => 1,
            "isDone" => TRUE,
        ],
        [
            "title" => "Встреча с другом",
            "dueDate" => "22.12.2019",
            "categoryId" => 0,
            "isDone" => FALSE,
        ],
        [
            "title" => "Купить корм для кота",
            "dueDate" => NULL,
            "categoryId" => 3,
            "isDone" => FALSE,
        ],
        [
            "title" => "Заказать пиццу",
            "dueDate" => NULL,
            "categoryId" => 3,
            "isDone" => FALSE,
        ],
    ];

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
