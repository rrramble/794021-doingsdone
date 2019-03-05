<?php
include_once('../abstract-form.php');

class RegisterForm extends AbstractForm {
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
                'validationCb' => function() {
                    $value = $this->getValue('password');
                    return mb_strlen($value) > 0;
                }
            ];

            $this->Field['passwordHash'] = [
                'isPublic' => true,
            ];

            $this->Field['userName'] = [
                'formTagName' => 'name',
                'isPublic' => true,
                'validationCb' => function() {
                    $value = $this->getValue('userName');
                    return mb_strlen($value) > 0;
                }
            ];

            parent::__construct();

            $password = $this->getValue('password');
            $this->Field['passwordHash'] = password_hash($password, PASSWORD_DEFAULT);
        }

    } // class RegisterForm

    ?>
