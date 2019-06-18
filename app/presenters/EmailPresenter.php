<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Application\UI\Form;



final class EmailPresenter extends Nette\Application\UI\Presenter
{

    public function actionEmail()
    {
      $email = new Message;

      $email->setFrom('shadowraiderr@gmail.com')
            ->addTo('shadowraider@seznam.cz')
            ->setSubject('test')
            ->setBody('this works');

      $mailer = new SendmailMailer;
      $mailer->send($email);




    }
}
