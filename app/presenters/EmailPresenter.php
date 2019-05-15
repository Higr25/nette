<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Application\UI\Form;



final class EmailPresenter extends Nette\Application\UI\Presenter
{
    // public $mailer;
    //
    // public function __construct(Nette\Mail\IMailer $mailer)
    // {
    //     $this->mailer = $mailer;
    // }

    public function actionSendEmail()
    {
      $email = new Message;

      $email->setFrom('shadowraiderr@gmail.com')
            ->addTo('shadowraider@seznam.cz')
            ->setSubject('test')
            ->setBody('this works');

      $mailer = new SendmailMailer;
      $mailer->send($email);

      // echo '<pre>' , var_dump($email) , '</pre>';
      //
      // $to = 'shadowraider@seznam.cz';
      // $subject = 'This is subject';
      // $message = 'This is simple text message.';
      // $header = 'From:shadowraiderr@gmail.com \r\n';
      // $retval = mail($to,$subject,$message,$header);
      // if( $retval == true )
      // {
      //   echo "Message sent successfully...";
      // }
      // else
      // {
      //   echo "Message could not be sent...";
      // }



    }
}
