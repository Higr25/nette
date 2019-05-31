<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\UserManager;
use Nette\Security\User;
use Nette\Security\Identity;

final class SchedulePresenter extends Nette\Application\UI\Presenter
{
    private $database;
    private $user;
    private $userManager;

    public function __construct(Nette\Database\Context $database, User $user, UserManager $userManager)
    {
        $this->database = $database;
        $this->user = $user;
        $this->userManager = $userManager;
    }


    public function renderSchedule()
    {
         $this->template->shows = $this->database->table('schedule');
         $this->template->Pozadi = true;

         // echo '<pre>' , var_dump($time) , '</pre>';
         // die();

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
}
