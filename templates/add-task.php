<?php
if (!isset($data)) {
  header("Location: /");
  die();
};

if (!isset($data["projects"])) {
    $data["projects"] = [];
};

if (!isset($data["postProjectId"])) {
  $data["postProjectId"] = null;
};

$CLASS_INPUT_ERROR = 'form__input--error';
?>
  <h2 class="content__main-heading">Добавление задачи</h2>

  <form class="form"  action="/pages/add-task.php" method="post" enctype="multipart/form-data">
    <div class="form__row">
      <label class="form__label" for="name">Название <sup>*</sup></label>

      <input
          class="form__input
            <?= isset($data["taskTitleIvalidMessage"]) ? strip_tags($CLASS_INPUT_ERROR) : ""; ?>"

            type="text" name="name" id="name"
            value="<?= isset($data["postTaskTitle"]) ? strip_tags($data["postTaskTitle"]) : ""; ?>"

            placeholder="Введите название">

      <?php if($data["taskTitleIvalidMessage"]): ?>
        <p class="form__message">
          <?= isset($data["taskTitleIvalidMessage"]) ? strip_tags($data["taskTitleIvalidMessage"]) : ""; ?>
        </p>
      <?php endif; ?>

    </div>

    <div class="form__row">
      <label class="form__label" for="project">Проект</label>

        <select class="form__input form__input--select" name="project" id="project">
        <?php foreach($data["projects"] as $project): ?>
          <?php 
            if (!isset($project["id"]) || !isset($project["title"])) {
              continue;
            };
          ?>
          <option
            value="<?= strip_tags($project['id']); ?>"
            <?= $project['id'] === $data["postProjectId"] ? "selected" : ""; ?>
          >
            <?= strip_tags($project['title']); ?>
          </option>
          <?php endforeach; ?>
        </select>
    </div>

    <div class="form__row">
      <label class="form__label" for="date">Дата выполнения</label>

      <input
        class="form__input form__input--date"
        type="date" name="date" id="date"
        value=""
        placeholder="Введите дату в формате ДД.ММ.ГГГГ">
      <?php if ($data["dueDateIvalidMessage"]): ?>
        <p class="form__message">
          <?= strip_tags($data["dueDateIvalidMessage"]); ?>
        </p>
      <?php endif; ?>
    </div>

    <div class="form__row">
      <label class="form__label" for="preview">Файл</label>
      <div class="form__input-file">
        <input class="visually-hidden" type="file" name="preview" id="preview" value="">
        <label class="button button--transparent" for="preview">
          <span>Выберите файл</span>
        </label>
      </div>
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="" value="Добавить">
    </div>

    <?php if ($data["formOverallErrorMessage"]): ?>
      <p class="form__message">
        <?= strip_tags($data["formOverallErrorMessage"]); ?>
      </p>
    <?php endif; ?>

  </form>
