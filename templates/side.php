<?php
if (!isset($data)) {
    header("Location: /");
    die();
};

$selectedProjectId = (integer)($data["projectId"] ?? 0);
$userId = (integer)($data['user']['id'] ?? 0);
$projects = $data["projects"] ?? [];
$tasks = $data["tasks"] ?? [];

const SELECTED_ITEM_CLASS = "main-navigation__list-item--active";
?>
<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">

            <?php foreach ($data["projects"] as $project): ?>

                <?php
                    $projectId = (integer)($project['id'] ?? 0);
                    $cssClass = $projectId === $selectedProjectId ? SELECTED_ITEM_CLASS : "";
                    $url = $projectId ? getProjectUrl($projectId) : "";
                    $title = $project['title'] ?? "";
                    $count = getTasksCount($projectId, $userId, $tasks);
                ?>

                <li class="main-navigation__list-item">
                    <a
                        class="main-navigation__list-item-link <?= strip_tags($cssClass); ?>"
                        href="<?= strip_tags($url); ?>">
                        <?= strip_tags($title); ?>
                    </a>
                    <span class="main-navigation__list-item-count">
                        <?= strip_tags($count); ?>
                    </span>
                </li>
            <?php endforeach; ?>

        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button"
    href="/pages/add-project.php" target="project_add">Добавить проект</a>
</section>
