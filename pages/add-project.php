<?php
include_once('../functions.php');
include_once('../db-api.php');
include_once('../session.php');
include_once('add-project-form.php');

const WEBPAGE_TITLE = 'Добавление проекта';
const SCRIPT_NAME_IF_SUCCESS = '/index.php';
const SCRIPT_NAME_IF_FAILURE = 'add-project.php';
const FormMessage = [
    'OVERALL_ERROR' => 'Пожалуйста, исправьте ошибки в форме',
    'NO_TITLE_ERROR' => 'Нужно указать название',
    'TITLE_ALREADY_EXISTS' => 'Название уже существует',
];

$db = new DbApi();
$form = new AddProjectForm();
$session = new Session();

$user = $session->getUserData();

$projects = getAdaptedProjects($db->getProjects());
$tasks = getAdaptedTasks($db->getTasks($user["id"]));

$isTitleValid = true;
$postTaskTitle = '';
$taskTitleIvalidMessage = '';

if ($form->isMethodPost()) {
    $isTitleValid = $form->isValid() && !isTitleExist($form->getValuePublic("title"), $projects);
    if ($isTitleValid) {
        $values = $form->getFieldsPublic();
        $values['authorId'] = (integer)$user['id'];
        if ($db->addProject($values)) {
            header('Location: ' . SCRIPT_NAME_IF_SUCCESS);
            die();
        };
        header('Location: ' . SCRIPT_NAME_IF_FAILURE);
    };
    
    $postTaskTitle = $form->getValuePublic('title');
    if (!$isTitleValid && mb_strlen($postTaskTitle) <= 0) {
        $taskTitleIvalidMessage = FormMessage['NO_TITLE_ERROR'];
    } else {
        $taskTitleIvalidMessage = FormMessage['TITLE_ALREADY_EXISTS'];
    };
}

$layoutData = [
    "data" => [
        "pageTitle" => WEBPAGE_TITLE,
        "projects" => $projects,
        "tasks" => $tasks,
        
        "isTitleValid" => $isTitleValid,
        "currentUser" => $user,
        "postTaskTitle" => $postTaskTitle,
        "taskTitleIvalidMessage"=> $taskTitleIvalidMessage,
        "formOverallErrorMessage" => FormMessage['OVERALL_ERROR'],
        "user" => $session->getUserData(),
    ],
];

$layoutData["data"]["components"] = [
    "main" => include_template("add-project.php", $layoutData),
];

echo include_template("layout.php", $layoutData);
