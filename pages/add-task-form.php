<?php
include_once('../abstract-form.php');

class AddTaskForm extends AbstractForm {
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
                $value = (integer)$this->getValue('projectId');
                return is_int($value) && $value >= 0;
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
        
                $dueDateUnixTime = strtotime($value);
                if (!$dueDateUnixTime) {
                    return false;
                };
                $todayUnixTime = strtotime(date("d.m.Y"));
                return $dueDateUnixTime >= $todayUnixTime;
            },
        ];

        $this->Field['savedFileName'] = [
            'isPublic' => true,
        ];

        $this->Field['originalFileName'] = [
            'isPublic' => true,
        ];

        if (isset($_FILES['preview'])) {
            $this->Field['savedFileName']['value'] = $_FILES['preview']['tmp_name'] ?? null;
            $this->Field['originalFileName']['value'] = $_FILES['preview']['name'] ?? null;
        };

        parent::__construct();
    }

} // class AddTaskForm
