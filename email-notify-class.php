<?php

require_once("vendor/autoload.php");

/*
 * Mailer credentials should be specified in the following constants:
 * MAIL_SERVER
 * MAIL_PORT
 * FROM_EMAIL
 * MAILBOX_USERNAME
 * MAILBOX_PASSWORD
 *
 * There also might be need in tuning account settings on the email server
 *
 * 
 * Для отправки рассылки по электронной почте необходимо определить константы:
 * MAIL_SERVER
 * MAIL_PORT
 * FROM_EMAIL
 * MAILBOX_USERNAME
 * MAILBOX_PASSWORD
 * 
 * Также возможно потребуется изменить настройки почтовой учетной записи
 * на почтовом сервере.
 */

const MAIL_SERVER = "smtp.gmail.com";
const MAIL_PORT = 465;
const MAIL_PROTOCOL = "ssl";
const FROM_EMAIL = "";
const MAILBOX_EMAIL = "";
const MAILBOX_PASSWORD = "";


// Class related constants

const FROM_OBJECT = [
    FROM_EMAIL => "Doings-Done upcoming tasks mailer",
];
const MESSAGE_SUBJECT = "Уведомление от сервиса «Дела в порядке»";
const EMAIL_TEMPLATE_SCRIPT = "email.php";


class EmailNotifyClass {

    /**
     * __construct
     *
     * @return self
     */
    public function __construct()
    {
        if (!MAIL_PROTOCOL || !MAILBOX_EMAIL || !MAILBOX_PASSWORD) {
            throw new Exception("Class 'EmailNotifyClass' in '/email-notify-class.php' should be provided with email credentials in the constants section.");
        };

        $this->mailTransport = new Swift_SmtpTransport(MAIL_SERVER, (integer)MAIL_PORT, MAIL_PROTOCOL);
        $this->mailTransport->setUsername(MAILBOX_EMAIL);
        $this->mailTransport->setPassword(MAILBOX_PASSWORD);
        $this->mailer = new Swift_Mailer($this->mailTransport);
    }


    /**
     * sendUpcomingNotification
     *
     * @param  mixed $user
     * @param  array $tasks
     *
     * @return boolean
     */
    public function sendUpcomingNotification($user, $tasks)
    {
        if (
            !isset($user["email"]) ||
            !filter_var($user["email"], FILTER_VALIDATE_EMAIL) ||
            !$tasks
        ) {
            return false;
        };

        $message = new Swift_Message();
        $message->setSubject(MESSAGE_SUBJECT);
        $message->setFrom(FROM_OBJECT);
        $message->setBcc($user["email"]);

        $data = [
            "tasks" => $tasks,
            "user" => $user,
        ];

        $messageContent = include_template(EMAIL_TEMPLATE_SCRIPT, $data);
        $message->setBody($messageContent, "text/html"); 

        $result = $this->mailer->send($message);
        return true;
    }

} // class EmailNotifyClass
