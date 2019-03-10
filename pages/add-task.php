<?php
include_once('../functions.php');
include_once('../db-api.php');
include_once('../session.php');
include_once('add-task-form.php');

$WEBPAGE_TITLE = 'Добавление задачи';
$SCRIPT_NAME_IF_SUCCESS = '/index.php';
$SCRIPT_NAME_IF_FAILURE = './add-task.php';
$FormMessage = [
    'OVERALL_ERROR' => 'Пожалуйста, исправьте ошибки в форме',
    'NO_TITLE_ERROR' => 'Нужно указать название',
    'TITLE_ALREADY_EXISTS' => 'Название уже существует',
    'DATE_MUST_BE_IN_FUTURE' => 'Дата должна быть в будущем'
];

$db = new DbApi();
$form = new AddForm();
$session = new Session();

$user = $session->getUserData();

$projects = getAdaptedProjects($db->getProjects());
$tasks = getAdaptedTasks($db->getTasks($user["id"]));

$isTitleValid = true;
$postTaskTitle = '';

$isDueDateValid = true;
$dueDateInInputType = '';
$taskTitleIvalidMessage = '';
$dueDateIvalidMessage = '';

if ($form->isMethodPost()) {
    $isTitleValid = $form->getTitleValidity() && !isTaskExists($form->getTitle(), $tasks);
    $isDueDateValid = $form->getDueDateValidity();
    $isProjectIdValid = $form->getProjectIdValidity() && isProjectIdExists($form->getProjectId(), $projects);
    
    if ($isTitleValid && $isDueDateValid && $isProjectIdValid) {
        $values = $form->getValues();
        $values['id'] = $session->getUserData()['id'];
        $isAddedCorrectly = $db->addTask($values);
        if ($isAddedCorrectly) {
            header('Location: ' . $SCRIPT_NAME_IF_SUCCESS);
            die();
        };
        header('Location: ' . $SCRIPT_NAME_IF_FAILURE);
    };
    
    $postTaskTitle = $form->getTitle();
    if (!$isTitleValid && mb_strlen($postTaskTitle) <=0) {
        $taskTitleIvalidMessage = $FormMessage['NO_TITLE_ERROR'];
    } else {
        $taskTitleIvalidMessage = $FormMessage['TITLE_ALREADY_EXISTS'];
    };
    
    $dueDateReadable = $form->getDueDateReadable();
    $dueDateInInputType = convertDateReadableToHtmlFormInput($dueDateReadable);
    
    $dueDateIvalidMessage = !$isDueDateValid ?
    $FormMessage['DATE_MUST_BE_IN_FUTURE'] :
    '';
}

$layoutData = [
    "data" => [
        "pageTitle" => $WEBPAGE_TITLE,
        "projects" => $projects,
        "tasks" => $tasks,
        
        "isTitleValid" => $isTitleValid,
        "currentUser" => $user,
        "postTaskTitle" => $postTaskTitle,
        "taskTitleIvalidMessage"=> $taskTitleIvalidMessage,
        "dueDateInInputType" => $dueDateInInputType,
        "dueDateIvalidMessage" => $dueDateIvalidMessage,
        "formOverallErrorMessage" => $FormMessage['OVERALL_ERROR'],
        "isDueDateValid" => $isDueDateValid,
        "user" => $session->getUserData(),
    ],
];

$layoutData["data"]["components"] = [
    "main" => include_template("add-task.php", $layoutData),
];

echo include_template("layout.php", $layoutData);