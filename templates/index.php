<?php
if (!isset($data)) {
    header("Location: /");
    die();
};
?>
<?php
    $HtmlClasses = [
        "TASK_COMPLETED" => "task--completed",
        "TASK_IMPORTANT" => "task--important",
        "FILTER_ACTIVE" => "tasks-switch__item--active",
    ];
?>
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="post">
        <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/?filter=0" class="tasks-switch__item
                <?= $data["tasksFilter"] === 0 ? $HtmlClasses["FILTER_ACTIVE"] : ""; ?>">
                Все задачи
            </a>
            <a href="/?filter=1" class="tasks-switch__item
                <?= $data["tasksFilter"] === 1 ? $HtmlClasses["FILTER_ACTIVE"] : ""; ?>">
                Повестка дня
            </a>
            <a href="/?filter=2" class="tasks-switch__item
                <?= $data["tasksFilter"] === 2 ? $HtmlClasses["FILTER_ACTIVE"] : ""; ?>">
                Завтра
            </a>
            <a href="/?filter=3" class="tasks-switch__item
                <?= $data["tasksFilter"] === 3 ? $HtmlClasses["FILTER_ACTIVE"] : ""; ?>">
                Просроченные
            </a>
        </nav>

        <label class="checkbox">
            <input class="checkbox__input visually-hidden show_completed" type="checkbox"
                <?php if ($data["showCompleteTasks"]): ?>
                checked
                <?php endif; ?>
            >
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
        <?php foreach($tasks as $task): ?>
            <?php
                if (
                    ($data["showCompleteTasks"] || !$task["isDone"]) &&
                    ($task["projectId"] === (integer)$currentProjectId || $currentProjectId === NULL)
                ):
            ?>
                <?php
                    $classTaskCompleted = $task["isDone"] ? $HtmlClasses["TASK_COMPLETED"] : "";
                    $classTaskImportant = isDeadlineNear($task["dueDate"]) ? $HtmlClasses["TASK_IMPORTANT"] : "";
                ?>
                <tr class="tasks__item task <?= $classTaskCompleted; ?> <?= $classTaskImportant; ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden task__checkbox" type="checkbox"
                              value="<?= $task["id"]; ?>"
                              <?= $classTaskCompleted ? "checked" : ""; ?>
                            >
                            <span class="checkbox__text"><?php echo $task["title"] ?></span>
                        </label>
                    </td>

                    <td class="task__file">
                        <?php if(isset($task["filePath"]) && $task["filePath"]): ?>
                        <a class="download-link" href="<?= $task["filePath"]; ?>">Файл</a>
                        <?php endif; ?>
                    </td>

                    <td class="task__date"><?php echo $task["dueDate"] ?></td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>

    </table>
