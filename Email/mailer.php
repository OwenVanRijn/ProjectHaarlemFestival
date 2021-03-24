<?php

$root = realpath($_SERVER["DOCUMENT_ROOT"]);

require_once $root . "/lib/swiftmailer/vendor/autoload.php";

class mailer
{
    public function sendMail(string $recieverMail, string $subject, string $message){

        $transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
            ->setUsername('haarlemfestival2021@gmail.com')
            ->setPassword('79AUkc75ANVt4Aa')
        ;
        $mailer = new Swift_Mailer($transport);

        $message = (new Swift_Message($subject))
            ->setFrom(['haarlemfestival2021@gmail.com' => 'Haarlem Festival'])
            ->setTo([$recieverMail => 'visitor'])
            ->setBody($message)
        ;

        $result = $mailer->send($message);

    }
}