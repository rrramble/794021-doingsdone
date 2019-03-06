<?php

class AbstractForm {
    protected $State = [
        'isMethodPost' => null
    ];

    protected $Field = [];

    function __construct()
    {
        if (!$this->isMethodPost()) {
            return;
        }
        $this->saveFields();
        $this->checkAndSaveFieldsValidity();
    }

    /**
     * @return void
     */
    protected function checkAndSaveFieldsValidity()
    {
        foreach($this->Field as &$field) {
            if (isset($field['validationCb'])) {
                $field['isValid'] = $field['validationCb']($field['value']);
            };
        };
    }

    /**
     * @return boolean
     */
    protected function isFieldValid($fieldName)
    {
        if (!isset($this->Field[$fieldName])) {
            throw new Exception('No such field in form class: ' . $fieldName);
        };

        if (!isset($this->Field[$fieldName]['value'])) {
            throw new Exception('No "value" field to validate: ' . $fieldName);
        };

        if (!isset($this->Field[$fieldName]['validationCbName'])) {
            return true;
        };

        $callbackName = $this->Field[$fieldName]['validationCbName'];
        return $callbackName($this->Field[$fieldName]['value']);
    }


    /**
     * @param string $fieldName
     * @return boolean
     */
    public function getFieldValidity($fieldName)
    {
        if (!isset($this->Field[$fieldName])) {
            throw new Exception('No such field in form class: ' . $fieldName);
        };

        if (!isset($this->Field[$fieldName]['isValid'])) {
            return true;
        };

        return (boolean)$this->Field[$fieldName]['isValid'];
    }


    /**
     * @param string $formTagName
     * @return string
     */
    protected function getFormField($formTagName)
    {
        if (!isset($_POST[$formTagName])) {
            throw new Exception('No such field in HTML form: ' . $formTagName);
        };
        return trim($_POST[$formTagName]) ?? null;
    }


    public function getFieldsPublic()
    {
        return array_filter($this->Field, function($field) {
            if (isset($field['isPublic']) && $field['isPublic']) {
                return $field;
            };
        });
    }

    /**
     * @param string $fieldName
     * @return string
     */
    protected function getValue($fieldName) {
        if (!isset($this->Field[$fieldName])) {
            throw new Exception('No such field name in class: ' . $fieldName);
        };
        if (!isset($this->Field[$fieldName]['value'])) {
            throw new Exception('No "value" in field: ' . $fieldName);
        };
        return $this->Field[$fieldName]['value'];
    }

    /**
     * @param string $fieldName
     * @return string
     */
    public function getValuePublic($fieldName)
    {
        if (!isset($this->Field[$fieldName])) {
            throw new Exception('No such field name in class: ' . $fieldName);
        };
        if (!isset($this->Field['isPublic']) || !$this->Field['isPublic']) {
            return null;
        };
        return $this->getValue($fieldName);
    }

    /**
     * @return void
     */
    protected function saveFields()
    {
        foreach($this->Field as &$field) {
            if (isset($field['formTagName'])) {
                $field['value'] = $this->getFormField($field['formTagName']);
            };
        };
    }

    /**
     * @return boolean
     */
    public function isMethodPost()
    {
        if ($this->State['isMethodPost'] === null) {
            $this->State['isMethodPost'] =
                $_SERVER['REQUEST_METHOD'] === 'POST';
        };
        return $this->State['isMethodPost'];
    }


    /**
     * @return boolean
     */
    public function isValid()
    {
        $fieldKeys = array_keys($this->Field);
        $result = array_reduce($fieldKeys, function($accu, $fieldName) {
            return $accu && $this->getFieldValidity($fieldName);
        }, true);
        return $result;
    }

} // class AbstractForm
