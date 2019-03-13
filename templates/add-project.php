<?php
if (!isset($data)) {
    header("Location: /");
    die();
};

$CLASS_INPUT_ERROR = 'form__input--error';
?>
<h2 class="content__main-heading">Добавление проекта</h2>

<form class="form" action="add-project.php" method="post">
    <div class="form__row">
        <label class="form__label" for="project_name">Название <sup>*</sup></label>

        <input
            class="form__input <?= !$data['isTitleValid'] ? $CLASS_INPUT_ERROR : ""; ?>"
            type="text" name="name" id="project_name"
            value="<?= strip_tags($data["postTaskTitle"]); ?>"
            placeholder="Введите название проекта">

        <?php if(!$data['isTitleValid']): ?>
            <p class="form__message">
            <?= strip_tags($data["taskTitleIvalidMessage"]); ?>
            </p>
        <?php endif; ?>

    </div>

    <div class="form__row form__row--controls">
    <input class="button" type="submit" name="" value="Добавить">
    </div>
</form>
