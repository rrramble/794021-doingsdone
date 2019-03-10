<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
        <ul class="main-navigation__list">

            <?php foreach ($data["projects"] as $project): ?>
                <?php
                    $url = getProjectUrl($project['id']);
                    $title = strip_tags($project['title']);
                    $count = getTasksCount($project['id'], $data['user']['id'], $data["tasks"]);
                ?>
                <li class="main-navigation__list-item">
                    <a class="main-navigation__list-item-link" href="<?= $url; ?>">
                        <?= $title ?>
                    </a>
                    <span class="main-navigation__list-item-count">
                        <?= $count ?>
                    </span>
                </li>
            <?php endforeach; ?>

        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button"
    href="/pages/add.php" target="project_add">Добавить проект</a>
</section>