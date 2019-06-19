<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\TheatreManager;
use Nette\Utils\DateTime;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;
use Nette\Application\UI\ITemplate;


final class CompletedPresenter extends Nette\Application\UI\Presenter
{
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
      $this->database = $database;




    }

    public function renderCompleted(array $params)
    {


        $row = $this->database->table('schedule')
                              ->where('table_name', $params['tableName'])
                              ->fetch();

        $earlyTime = $row['datetime']->modify('-15 minutes');
        $early = $earlyTime->format('H:i');
        $params['early'] = $early;
        $showInfo = $this->database->table('schedule')
                                  ->where('table_name', $params['tableName'])
                                  ->fetch();

        $this->template->person = $params['person'];
        $this->template->email = $params['email'];
        $this->template->price = $params['price'];
        $this->template->early = $early;
        $this->template->seatNumbers = $params['seatNumbers'];
        $this->template->tableName = $params['tableName'];
        $this->template->showInfo = $showInfo;

        $emailParams = [
          'person' => $params['person'],
          'email' => $params['email'],
          'price' => $params['price'],
          'seatNumbers' => $params['seatNumbers'],
          'early' => $params['early'],
          'showName' => $showInfo['name'],
          'showDate' => $showInfo['date'],
          'showTime' => $showInfo['time']
        ];
       //  $template = $this->createTemplate();
       //  $template->setParameters($emailParams);
       //  $template->setFile(__DIR__ . '\templates\completed\email.latte');
       //  // dump($emailParams);
       //  // die();
       //  $email = new Message;
       //  $email->setFrom('Divadlo Harfa <divadlo@harfa.cz>')
       //        ->addTo($emailParams['email'])
       //        ->setSubject('Potvrzení Rezervace')
       //        ->setHtmlBody((string) $template);
       //
       // $mailer = new SendmailMailer;
       // $mailer->send($email);




    }

   //  protected function createTemplate()
   // {
   //     $template = $this->templateFactory->createTemplate();
   //     $template->getLatte()->addProvider('uiControl', $this->linkGenerator);
   //
   //     return $template;
   // }
}
