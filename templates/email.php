<?php

if (
    !isset($tasks) ||
    !isset($user) ||
    count($tasks) <= 0
) {
    header("Location: /");
    die();
};

$userName = $user["userName"] ?? "";

?>
<p>Уважаемый, <?= strip_tags($userName); ?>.</p>
<p>У вас запланированы задачи:</p>

<table border="1">
    <tr>
        <th width="65%">Название:</th>
        <th>Дата/время:</th>
    </tr>

    <?php foreach($tasks as $task): ?>
        <?php
            $title = $task["title"] ?? "";
            $date = $task["dueDate"] ?? "";
        ?>
        <tr>
            <td><?= strip_tags($title); ?></td>
            <td><?= strip_tags($date); ?></td>
        </tr>
    <?php endforeach; ?>
</table>
