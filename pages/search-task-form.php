<?php
include_once('abstract-form.php');

class SearchTaskForm extends AbstractForm {
    function __construct()
    {
        $this->Field["title"] = [
            'formTagName' => 'task-search',
            'value' => null,
            'isPublic' => true,
            'validationCb' => function() {
                $value = $this->getValue("title");
                return (boolean)mb_strlen($value) > 0;
            },
        ];

        parent::__construct();
    }

} // class TaskSearchForm
