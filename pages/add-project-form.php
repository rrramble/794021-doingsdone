<?php

  class AddProjectForm {
    private $PUBLIC_FOLDER = '/pub/';

    private $State = [
      'isMethodPost' => null
    ];

    private $Field = [
      'title' => [
        'formTagName' => 'name',
        'value' => null,
        'isValid' => false
      ],
    ];

    function __construct()
    {
      $this->State['isMethodPost'] = $this->isMethodPost();
      if (!$this->State['isMethodPost']) {
        return;
      }
      $this->getFormFields();
      $this->Field['title']['isValid'] = $this->isTitleValid();
    }

    private function getFormField($formTagName)
    {
      if (!isset($_POST[$formTagName])) {
        return null;
      };
      return trim($_POST[$formTagName]) ?? null;
    }

    private function getFormFields()
    {
      foreach($this->Field as &$field) {
        $field['value'] = $this->getFormField($field['formTagName']);
      };
      unset($field);
    }

    public function getTitle()
    {
      return $this->Field['title']['value'];
    }

    public function getTitleValidity()
    {
      return $this->Field['title']['isValid'];
    }

    public function isMethodPost()
    {
      if ($this->State['isMethodPost'] === null) {
        $this->State['isMethodPost'] = ($_SERVER['REQUEST_METHOD'] === 'POST');
      };
      return $this->State['isMethodPost'];
    }

    private function isTitleValid()
    {
      $value = $this->Field['title']['value'];
      return mb_strlen($value) > 0;
    }

    public function getValues()
    {
      $item = array();
      $item['title'] = $this->Field['title']['value'];
      return $item;
    }

  } // class AddProjectForm

?>
