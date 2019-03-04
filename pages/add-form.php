<?php

  class AddForm {
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
      $this->Field['title']['isValid'] = $this->isTitleValid();
      $this->Field['projectId']['isValid'] = $this->isProjectIdValid();
      $this->Field['dueDate']['isValid'] = $this->isDueDateValid();
      $this->Field['file']['isValid'] = true;
    }

    private function isProjectIdValid()
    {
      $value = $this->Field['projectId']['value'];
      return ($value === (string)(integer)$value || (integer)$value > 0);
    }

    public function getDueDateValidity()
    {
      return $this->Field['dueDate']['isValid'];
    }

    public function getDueDateReadable()
    {
      return $this->Field['dueDate']['value'];
    }

    private function getFormField($formTagName)
    {
      return trim($_POST[$formTagName]) ?? null;
    }

    private function getFormFields()
    {
      foreach($this->Field as &$field) {
        $field['value'] = $this->getFormField($field['formTagName']);
      };
      unset($field);
    }

    /**
     * @return integer
     */
    public function getProjectId()
    {
      return (integer)$this->Field['projectId']['value'];
    }

    /**
     * @return boolean
     */
    public function getProjectIdValidity()
    {
      return $this->Field['projectId']['isValid'];
    }

    public function getTitle()
    {
      return $this->Field['title']['value'];
    }

    public function getTitleValidity()
    {
      return $this->Field['title']['isValid'];
    }

    private function isDueDateValid()
    {
      $value = $this->Field['dueDate']['value'];
      if (mb_strlen($value) <= 0) {
        return true;
      };

      $date = strtotime($value);
      if (!$date) {
        return false;
      };
      return $date > strtotime('now');
    }

    public function isMethodPost()
    {
      if ($this->State['isMethodPost'] !== null) {
        return $this->State['isMethodPost'];
      }
      return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    private function isTitleValid()
    {
      $value = $this->Field['title']['value'];
      return mb_strlen($value) > 0;
    }

    public function getValues()
    {
      $item = array();
      $item['project_id'] = $this->Field['projectId']['value'];
      $item['title'] = $this->Field['title']['value'];
      $item['dueDate'] = $this->Field['dueDate']['value'];

      if (isset($_FILES['preview'])) {
        $item['savedFileName'] = $_FILES['preview']['tmp_name'];
        $item['userFileName'] = $_FILES['preview']['name'];
      };
      var_dump($item);
      die();
      return $item;
    }

  } // class Form

?>
