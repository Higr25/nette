<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\UserManager;
use Nette\Security\User;
use Nette\Security\Identity;

final class FormPresenter extends Nette\Application\UI\Presenter
{

  private $user;
  private $userManager;

  private $database;

  public function __construct(User $user, UserManager $userManager, Nette\Database\Context $database)
  {
      $this->user = $user;
      $this->userManager = $userManager;

      $this->database = $database;
  }

        //Form

      protected function createComponentEmployeeForm(): Form
      {
        $form = new Form;


         $form->addText('date', 'Datum:')
              ->setRequired();

         $form->addText('work_start', 'Začátek Práce:')
              ->setRequired();

         $form->addText('work_end', 'Konec Práce:')
              ->setRequired();

         $form->addTextArea('comment', 'Náplň Práce:')
              ->setRequired()
              ->getControlPrototype()
              ->addClass('comment');

         $form->addSubmit('submit', 'Odeslat')
              ->getControlPrototype()
              ->addClass('btn btn-primary');


         $form->onSuccess[] = [$this, 'employeeFormSucceeded'];

         return $form;

      }


    public function employeeFormSucceeded(Form $form, \stdClass $values)
    {

      $user = $this->user;
      $id = $this->getParameter('id');
      $date = $values->date;
      $workStart = new \DateTime($date . ' ' . $values->work_start);
      $workEnd = new \DateTime($date . ' ' . $values->work_end);

      if ($id) {

        $employeeReport = $this->database->table('employee_report')->get($id);
        $employeeReport->update([
          'first_name' => $user->getIdentity()->first_name,
          'last_name' => $user->getIdentity()->last_name,
          'work_start' => $workStart,
          'work_end' => $workEnd,
          'comment' => $values->comment,
          'updated_at' => new \DateTime(),
        ]);

      } else {

        $this->database->table('employee_report')->insert([
          'first_name' => $user->getIdentity()->first_name,
          'last_name' => $user->getIdentity()->last_name,
          'work_start' => $workStart,
          'work_end' => $workEnd,
          'comment' => $values->comment,
          'created_at' => new \DateTime(),
          'updated_at' => new \DateTime(),
        ]);
      }

      $this->flashMessage('Výkaz byl odeslán', 'success');
      $this->redirect('this');
    }



    public function renderEdit(int $id)
    {

      $employeeReport = $this->database->table('employee_report')->get($id);
      if (!$employeeReport){
        $this->error('Výkaz s tímto číslem neexistuje');
      }
      $employeeReportArray = $employeeReport->toArray();
      $workStart = $employeeReportArray['work_start'];
      $employeeReportArray['date'] = $workStart->format('Y-m-d');
      $employeeReportArray['work_start'] = $workStart->format('H:i');
      $workEnd = $employeeReportArray['work_end'];
      $employeeReportArray['work_end'] = $workEnd->format('H:i');

      $this['employeeForm']->setDefaults($employeeReportArray);

    }



}
