<?php

  class AddForm {
    private $State = [
      'isMethodPost' => null
    ];

    private $Field = [
      'title' => [
        'formTagName' => 'name',
        'value' => null,
        'isValid' => false
      ],
      'projectId' => [
        'formTagName' => 'project',
        'value' => null,
        'isValid' => false
      ],
      'dueDate' => [
        'formTagName' => 'date',
        'value' => null,
        'isValid' => false
      ],
      'file' => [
        'formTagName' => 'preview',
        'value' => null,
        'isValid' => false
      ]
    ];

    function __construct()
    {
      $this->State['isMethodPost'] = $this->isMethodPost();
      if (!$this->State['isMethodPost']) {
        return;
      }
      $this->getFormFields();
      $this->checkValidityTitle();
      $this->checkValidityProjectId();
      $this->checkValidityDueDate();
      $this->checkValidityFile();
    }

    private function checkValidityDueDate()
    {
      $value = $this->Field['dueDate']['value'];
      if (false) {
        $this->Field['projectId']['isValid'] = false;
      } else {
        $this->Field['projectId']['isValid'] = true;        
      }
    }

    private function checkValidityFile()
    {
      $this->Field['file']['isValid'] = true;
    }

    private function checkValidityProjectId()
    {
      $value = $this->Field['projectId']['value'];
      if ($value !== (string)(integer)$value || (integer)$value <= 0) {
        $this->Field['projectId']['isValid'] = false;
      } else {
        $this->Field['projectId']['isValid'] = true;        
      }
    }

    private function checkValidityTitle()
    {
      $trimmedValue = trim($this->Field['title']['value']);
      $this->Field['title']['isValid'] = mb_strlen($trimmedValue) > 0;
    }

    private function getFormField($formTagName)
    {
      return $_POST[$formTagName] ?? null;
    }

    private function getFormFields()
    {
      foreach($this->Field as &$field) {
        $field['value'] = $this->getFormField($field['formTagName']);
      };
      unset($field);
    }

    public function isMethodPost()
    {
      if ($this->State['isMethodPost'] !== null) {
        return $this->State['isMethodPost'];
      }
      return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

  } // class Form

?>
