<?php

function include_template($name, $data)
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

function getTasksCategoryCount($tasks, $categoryName, $categories)
{
    $count = 0;
    foreach($tasks as $task) {
        $thisTaskCategoryName = $categories[$task["categoryId"]];
        if ($thisTaskCategoryName === $categoryName) {
            $count++;
        };
    };
    return $count;
}
