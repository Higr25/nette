<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\TheatreManager;
use Nette\Utils\DateTime;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;

final class CompletedPresenter extends Nette\Application\UI\Presenter
{
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;

        $email = new Message;

        $email->setFrom('shadowraiderr@gmail.com')
              ->addTo('shadowraider@seznam.cz')
              ->setSubject('test')
              ->setBody('this works');

        $mailer = new SendmailMailer;
        $mailer->send($email);

    }

    public function renderCompleted(array $seatNumbers, $tableName, $price, $email)
    {
        $row = $this->database->table('schedule')
                              ->where('table_name', $tableName)
                              ->fetch();

        $earlyTime = $row['datetime']->modify('-15 minutes');
        $early = $earlyTime->format('H:i');

        $this->template->email = $email;
        $this->template->price = $price;
        $this->template->early = $early;
        $this->template->seatNumbers = $seatNumbers;
        $this->template->tableName = $tableName;
        $this->template->showInfo = $this->database->table('schedule')
                                  ->where('table_name', $tableName)
                                  ->fetch();

    }



}
