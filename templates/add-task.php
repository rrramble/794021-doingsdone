<?php
if (!isset($data)) {
  header("Location: /");
  die();
};

$projects = $data["projects"] ?? [];
$taskTitleInvalidMessage = $data["taskTitleIvalidMessage"] ?? "";
$postTaskTitle = $data["postTaskTitle"] ?? "";
$postProjectId = (integer)($data["postProjectId"] ?? 0);

$CLASS_INPUT_ERROR = 'form__input--error';
?>
  <h2 class="content__main-heading">Добавление задачи</h2>

  <form class="form"  action="/pages/add-task.php" method="post" enctype="multipart/form-data">
    <div class="form__row">
      <label class="form__label" for="name">Название <sup>*</sup></label>

      <input
          class="form__input
            <?= $taskTitleInvalidMessage ? strip_tags($CLASS_INPUT_ERROR) : ""; ?>"
            type="text" name="name" id="name"
            value="<?= strip_tags($postTaskTitle); ?>"
            placeholder="Введите название">

      <?php if ($taskTitleInvalidMessage): ?>
        <p class="form__message">
          <?= strip_tags($taskTitleInvalidMessage); ?>
        </p>
      <?php endif; ?>

    </div>

    <div class="form__row">
      <label class="form__label" for="project">Проект</label>

        <select class="form__input form__input--select" name="project" id="project">
          <option value="0"
            <?= $postProjectId === 0 ? " selected " : ""; ?>
          ></option>

          <?php foreach($projects as $project): ?>
            <?php
              $projectTitle = $project["title"] ?? "";
              if (!isset($project["id"])) {
                continue;
              };
            ?>
            <option
              value="<?= strip_tags($project['id']); ?>"
              <?= $project['id'] === $postProjectId ? "selected" : ""; ?>
            >
              <?= strip_tags($projectTitle); ?>
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

    <?php if (isset($data["formOverallErrorMessage"]) && $data["formOverallErrorMessage"]): ?>
      <p class="form__message">
        <?= strip_tags($data["formOverallErrorMessage"]); ?>
      </p>
    <?php endif; ?>

  </form>
