<?php
include_once('../abstract-form.php');

class AddProjectForm extends AbstractForm {
    function __construct()
    {
        $this->Field["title"] = [
            'formTagName' => 'name',
            'value' => null,
            'isPublic' => true,
            'validationCb' => function() {
                $value = $this->getValue("title");
                return (boolean)mb_strlen($value) > 0;
            },
        ];

        parent::__construct();
    }

} // class AddProjectForm
