<?php

  class AddForm {
    private $State = [
      'isReceived' => false,
      'isChecked' => false,
      'isValid' => false,
      'isFileReceived' => false
    ];

    private $Field = [
      'title' => null,
      'projectId' => null,
      'fileName' => null,
    ];

    function __construct()
    {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return false;
      };

      // form fields:
      // 'name'
      // 'project'
      // 'date'
      // 'preview'
    }

    function checkValidity()
    {
    }


  } // class Form

?>
