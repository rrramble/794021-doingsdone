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

    }

    function checkValidity()
    {

    }


  } // class Form

  if ($Form['isReceived']) {
    $Form['isValid'] = $FormAdd->checkValidity();
    $Form['isFileReceived'] = $FormAdd->isFileReceived();
    
    $Form['isChecked'] = true;
  };

?>
