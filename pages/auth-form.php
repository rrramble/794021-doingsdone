<?php
include_once('../abstract-form.php');

class AuthForm extends AbstractForm {
    function __construct()
    {
        $this->Field['email'] = [
            'formTagName' => 'email',
            'isPublic' => true,
            'validationCb' => function() {
                $value = $this->getValue('email');
                return (boolean)filter_var($value, FILTER_VALIDATE_EMAIL);
            }
        ];

        $this->Field['password'] = [
            'formTagName' => 'password',
            'isPublic' => true,
        ];

        parent::__construct();
    }

} // class AuthForm
