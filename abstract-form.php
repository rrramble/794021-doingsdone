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
        };

        $this->saveFieldsFromForm();
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
    public function isFieldValid($fieldName)
    {
        if (!isset($this->Field[$fieldName])) {
            throw new Exception('No such field in form class: ' . $fieldName);
        };

        if (!isset($this->Field[$fieldName]['validationCb'])) {
            return true;
        };
        $callbackName = $this->Field[$fieldName]['validationCb'];
        return $callbackName($this->getValue($fieldName));
    }


    /**
     * @param string $fieldName
     * @return boolean
     */
    public function getFieldValidity($fieldName)
    {
        if (!isset($this->Field[$fieldName])) {
            throw new Exception("No such field in form class: '" . $fieldName . "'");
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
        $value = $_POST[$formTagName] ?? null;
        return trim($value);
    }

    public function getFieldsPublic()
    {
        $result = [];
        foreach ($this->Field as $key => $item) {
            if (isset($item['isPublic']) && $item['isPublic']) {
                $result[$key] = $this->getValue($key);
            };
        };
        return $result;
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
        $field = $this->Field[$fieldName];
        if (!isset($field['isPublic']) || !$field['isPublic']) {
            throw new Exception('Value is not public: ' . $fieldName);
        };
        return $this->getValue($fieldName);
    }


    /**
     * @return void
     */
    protected function saveFieldsFromForm()
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
