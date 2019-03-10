<?php
$CLASS_INPUT_ERROR = 'form__input--error';
?>
  <h2 class="content__main-heading">Добавление задачи</h2>

  <form class="form"  action="/pages/add-task.php" method="post" enctype="multipart/form-data">
    <div class="form__row">
      <label class="form__label" for="name">Название <sup>*</sup></label>

      <input
          class="form__input <?php if (!$data['isTitleValid']) {echo $CLASS_INPUT_ERROR;} ?>"
          type="text" name="name" id="name"
          value="<?= $data["postTaskTitle"]; ?>"
          placeholder="Введите название">

      <?php if(!$data['isTitleValid']): ?>
        <p class="form__message">
          <?= $data["taskTitleIvalidMessage"]; ?>
        </p>
      <?php endif; ?>

    </div>

    <div class="form__row">
      <label class="form__label" for="project">Проект</label>

        <select class="form__input form__input--select" name="project" id="project">
        <?php foreach($data["projects"] as $project): ?>
        <?php
          $title = $project['title'];
          $id = $project['id'];
        ?>
          <option value="<?= $id; ?>"><?= $title; ?></option>
          <?php endforeach; ?>
        </select>
    </div>

    <div class="form__row">
      <label class="form__label" for="date">Дата выполнения</label>

      <input
        class="form__input form__input--date"
        type="date" name="date" id="date"
        value="<?= $dueDateInInputType; ?>"
        placeholder="Введите дату в формате ДД.ММ.ГГГГ">
      <?php if (!$data["isDueDateValid"]): ?>
        <p class="form__message">
          <?= $data["dueDateIvalidMessage"]; ?>
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

    <?php if (!$data["isTitleValid"] || !$data["isDueDateValid"]): ?>
      <p class="form__message">
        <?= $data["formOverallErrorMessage"]; ?>
      </p>
    <?php endif; ?>

  </form>