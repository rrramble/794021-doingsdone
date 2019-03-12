<?php
include_once('../abstract-form.php');

class AddForm extends AbstractForm {
    function __construct()
    {
        $this->Field['title'] = [
            'formTagName' => 'name',
            'value' => null,
            'isPublic' => true,
            'validationCb' => function() {
                $value = $this->getValue("title");
                return (boolean)mb_strlen($value) > 0;
            },
        ];

        $this->Field['projectId'] = [
            'formTagName' => 'project',
            'value' => null,
            'isPublic' => true,
            'validationCb' => function() {
                $value = $this->getValue('projectId');
                return (string)$value === (string)(integer)$value;
            }
        ];

        $this->Field['dueDate'] = [
            'formTagName' => 'date',
            'value' => null,
            'isPublic' => true,
            'validationCb' => function() {
                $value = $this->getValue('dueDate');
                if (mb_strlen($value) <= 0) {
                    return true;
                };
        
                $date = strtotime($value);
                if (!$date) {
                    return false;
                };
                return $date > strtotime('now');
            },
        ];

        $this->Field['savedFileName'] = [
            'isPublic' => true,
        ];

        $this->Field['originalFileName'] = [
            'isPublic' => true,
        ];

        if (isset($_FILES['preview'])) {
            $this->Field['savedFileName']['value'] = $_FILES['preview']['tmp_name'];
            $this->Field['originalFileName']['value'] = $_FILES['preview']['name'];
        };

        parent::__construct();
    }

} // class AddTaskForm
