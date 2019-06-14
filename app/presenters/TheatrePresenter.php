<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\UserManager;
use App\Model\ReserveManager;
use Nette\Security\User;
use Nette\Security\Identity;

final class TheatrePresenter extends Nette\Application\UI\Presenter
{
    private $database;
    private $user;
    private $userManager;
    private $reserveManager;

    public function __construct(Nette\Database\Context $database, User $user, UserManager $userManager, ReserveManager $reserveManager)
    {
        $this->database = $database;
        $this->user = $user;
        $this->userManager = $userManager;
        $this->reserveManager = $reserveManager;
    }


    public function renderReserve($tableName): void
    {
         $this->template->show = $this->database->table('schedule')
                                ->where('table_name', $tableName)
                                ->fetch();
         $this->template->seatsFloor = $this->database->table($tableName)
                                      ->where('seat_number <= ?', 120);
         $this->template->seatsBalconyLeft = $this->database->table($tableName)
                                            ->where('seat_number >= ? AND seat_number <= ?', 121, 146);
         $this->template->seatsBalconyRight = $this->database->table($tableName)
                                              ->where('seat_number >= ? AND seat_number <= ?', 147, 172);
    }

    // echo '<pre>' , var_dump($show) , '</pre>';
    // die();

    public function renderCompleted(array $seatNumbers, $tableName)
    {

        $this->template->seatNumbers = $seatNumbers;
        $this->template->showInfo = $this->database->table('schedule')
                                  ->where('table_name', $tableName)
                                  ->fetch();
        $this->template->tableName = $tableName;
    }


    public function createComponentReservationForm()
    {
        $form = new Form;

        $form->addTextArea('seat_number')
             ->getControlPrototype()
             ->addClass('hidden')
             ->setRequired();

        $form->addText('person', 'Jméno a příjmení:')
             ->setRequired();

        $form->addText('person_email', 'E-mail:')
             ->setRequired();

        $form->addText('person_phone', 'Telefon:')
             ->setRequired();

        $form->addSubmit('submit', 'Rezervovat');


        $form->onSuccess[] = [$this->reserveManager, 'reserve'];

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

         $this->redirect('this');
       }

       public function actionOut()
       {
           $this->getUser()->logout(true);
           $this->flashMessage('Odhlášení bylo úspěšné.', 'success');
           $this->redirect('Schedule:schedule');
       }
}
