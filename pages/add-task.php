<?php
include_once('../functions.php');
include_once('../db-api.php');
include_once('../session.php');
include_once('add-task-form.php');

const WEBPAGE_TITLE = 'Добавление задачи';
const SCRIPT_NAME_IF_SUCCESS = '/index.php';
const SCRIPT_NAME_IF_FAILURE = './add-task.php';
const FormMessage = [
    'OVERALL_ERROR' => 'Пожалуйста, исправьте ошибки в форме',
    'NO_TITLE_ERROR' => 'Нужно указать название',
    'TITLE_ALREADY_EXISTS' => 'Название уже существует',
    'DATE_MUST_BE_TODAY_OR_FUTURE' => 'Дата должна быть сегодня или в будущем',
];

$session = new Session();
$db = new DbApi($session->getUserId());
$form = new AddTaskForm();

if ($form->isMethodPost()) {
    if ($form->isValid()) {
        $taskData = $form->getFieldsPublic();
        $taskData['userId'] = $session->getUserId();

        $isAddedCorrectly = $db->addTask($taskData);
        if ($isAddedCorrectly) {
            header('Location: ' . SCRIPT_NAME_IF_SUCCESS);
            die();
        };
        header('Location: ' . SCRIPT_NAME_IF_FAILURE);
    };

    $postTaskTitle = $form->getValuePublic('title');
    $taskTitleIvalidMessage = $form->getFieldValidity('title') ?
        '' :
        FormMessage['NO_TITLE_ERROR'];

    $postProjectId = $form->getValuePublic('projectId');

    $dueDateIvalidMessage = $form->getFieldValidity('dueDate') ?
        '' :
        FormMessage['DATE_MUST_BE_TODAY_OR_FUTURE'];
};

$layoutData = [
    "data" => [
        "pageTitle" => WEBPAGE_TITLE,
        "projects" => getAdaptedProjects($db->getProjects()),
        "tasks" => getAdaptedTasks($db->getTasks()),
        "user" => [
            "id" => $session->getUserId(),
            "userName" => $session->getUserName(),
        ],

        "postTaskTitle" => $postTaskTitle ?? "",
        "taskTitleIvalidMessage"=> $taskTitleIvalidMessage ?? "",

        "postProjectId" => (integer)($postProjectId ?? 0),
        "dueDateIvalidMessage" => $dueDateIvalidMessage ?? "",
        "formOverallErrorMessage" => !$form->isValid() ? FormMessage['OVERALL_ERROR'] : "",
    ],
];

$layoutData["data"]["components"] = [
    "main" => include_template("add-task.php", $layoutData),
];

echo include_template("layout.php", $layoutData);
