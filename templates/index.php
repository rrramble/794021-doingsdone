<?php
if (!isset($data)) {
    header("Location: /");
    die();
};

$currentProjectId = isset($data["projectId"]) ? (integer)$data["projectId"] : 0;
$tasksFilterId = $data["tasksFilterId"] ?? 0;
$postTitle = $data["postTitle"] ?? "";
$showCompleteTasks = (boolean)($data["showCompleteTasks"] ?? false);

$HtmlClasses = [
    "TASK_COMPLETED" => "task--completed",
    "TASK_IMPORTANT" => "task--important",
    "FILTER_ACTIVE" => "tasks-switch__item--active",
];
?>
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="post">
        <input class="search-form__input" type="text" name="task-search"
            value="<?= strip_tags($postTitle); ?>" placeholder="Поиск по задачам"
        >

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/?filter=0" class="tasks-switch__item
                <?= $tasksFilterId === 0 ? strip_tags($HtmlClasses["FILTER_ACTIVE"]) : ""; ?>">
                Все задачи
            </a>
            <a href="/?filter=1" class="tasks-switch__item
                <?= $tasksFilterId === 1 ? strip_tags($HtmlClasses["FILTER_ACTIVE"]) : ""; ?>">
                Повестка дня
            </a>
            <a href="/?filter=2" class="tasks-switch__item
                <?= $tasksFilterId === 2 ? strip_tags($HtmlClasses["FILTER_ACTIVE"]) : ""; ?>">
                Завтра
            </a>
            <a href="/?filter=3" class="tasks-switch__item
                <?= $tasksFilterId === 3 ? strip_tags($HtmlClasses["FILTER_ACTIVE"]) : ""; ?>">
                Просроченные
            </a>
        </nav>

        <label class="checkbox">
            <input class="checkbox__input visually-hidden show_completed" type="checkbox"
                <?php if ($showCompleteTasks): ?>
                checked
                <?php endif; ?>
            >
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
        <?php foreach($filteredTasks as $task): ?>
            <?php
                $isTaskDone = (boolean)($task["isDone"] ?? false);
                $taskDueDate = $task["dueDate"] ?? "";
                $taskProjectId = (integer)($task["projectId"] ?? 0);
                $classTaskCompleted = $isTaskDone ? $HtmlClasses["TASK_COMPLETED"] : "";
                $classTaskImportant = isDeadlineNear($taskDueDate) ? $HtmlClasses["TASK_IMPORTANT"] : "";
                if (
                    ($showCompleteTasks || !$isTaskDone) &&
                    ($currentProjectId === 0 || $taskProjectId === $currentProjectId)
                ):
            ?>
                <tr class="tasks__item task <?= strip_tags($classTaskCompleted); ?> <?= strip_tags($classTaskImportant); ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden task__checkbox" type="checkbox"
                              value="<?= strip_tags($task["id"]); ?>"
                              <?= $classTaskCompleted ? "checked" : ""; ?>
                            >
                            <span class="checkbox__text"><?= strip_tags($task["title"]); ?></span>
                        </label>
                    </td>

                    <td class="task__file">
                        <?php if(isset($task["filePath"]) && $task["filePath"]): ?>
                        <a class="download-link" href="<?= strip_tags($task["filePath"]); ?>">Файл</a>
                        <?php endif; ?>
                    </td>

                    <td class="task__date">
                        <?= strip_tags($taskDueDate); ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>

    </table>
