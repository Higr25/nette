<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\UserManager;
use App\Model\TheatreManager;
use Nette\Security\User;
use Nette\Security\Identity;

final class SchedulePresenter extends Nette\Application\UI\Presenter
{
    private $database;
    private $user;
    private $userManager;
    private $theatreManager;

    public function __construct(Nette\Database\Context $database, User $user, UserManager $userManager, TheatreManager $theatreManager)
    {
        $this->database = $database;
        $this->user = $user;
        $this->userManager = $userManager;
        $this->theatreManager = $theatreManager;
    }


    public function renderSchedule($tableVerify)
    {
         $this->template->shows = $this->database->table('schedule');
         $this->template->Pozadi = true;
         $this->template->tableVerify = $tableVerify;
         dump($tableVerify);

    }

    protected function createComponentCreatorForm(): Form
    {
      $form = new Form;

      $form->addText('date', 'Datum:')
           ->setRequired();

      $form->addText('time', 'Čas:')
           ->setRequired();

      $form->addText('name', 'Jméno:')
           ->setRequired();

      $form->addText('price', 'Cena:')
           ->setRequired();

      $form->addSubmit('add', 'Přidat')
           ->getControlPrototype()
           ->addClass('btn btn-primary');

      $form->onSuccess[] = [$this->theatreManager, 'addEvent'];

      return $form;
    }

    protected function createComponentLoginForm(): Form
    {
      $form = new Form;

      $form->addText('username', 'Login:')
           ->setRequired();

      $form->addPassword('password', 'Password:')
           ->setRequired();

       $form->addSubmit('login', 'Login')
            ->getControlPrototype()
            ->addClass('btn btn-primary');

       $form->onSuccess[] = [$this, 'authenticate'];

       return $form;

    }

    public function authenticate(form $form, \stdClass $values)
    {

      $credentials = [
        'username' => $values->username,
        'password' => $values->password,
      ];

      $this->getUser()->login($credentials['username'], $credentials['password']);

      $this->redirect('Schedule:schedule');
    }

    public function actionOut()
    {
        $this->getUser()->logout(true);
        $this->flashMessage('Odhlášení bylo úspěšné.', 'success');
        $this->redirect('Schedule:schedule');
    }

    public function actionDelete($id)
    {
      $row = $this->database->table('schedule')->get($id);
      $table_Name = $row['table_name'];
      $this->database->query("
        DELETE FROM `schedule` WHERE `id` = $id;
        DROP TABLE IF EXISTS `$table_Name`;
      ");
      $this->redirect('Schedule:schedule');
    }



}
