<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\UserManager;
use Nette\Security\User;
use Nette\Security\Identity;

final class TheatrePresenter extends Nette\Application\UI\Presenter
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


        $form->onSuccess[] = [$this, 'reserve'];

        return $form;
    }

    public function reserve(Form $form, $values)
    {

      $tableName = $this->getParameter('table_name');

      $seatNumberInput = $values['seat_number'];
      $seatNumbers = explode(' ', $seatNumberInput);

        foreach ($seatNumbers as $seatNumber) {
          $row = $this->database->table($tableName)
                 ->where('seat_number', $seatNumber)
                 ->fetch();
          $isFree = $row['free'];

        if ($isFree) {

           $seatNumberArray = [
             'seat_number' => $seatNumber,
             'person' => $values['person'],
             'person_email' => $values['person_email'],
             'person_phone' => $values['person_phone'],
             'free' => 0,
             'reserved' => 1];

           $seat = $this->database->table($tableName)
                 ->where('seat_number', $seatNumberArray['seat_number'])
                 ->update([
                     'person' => $seatNumberArray['person'],
                     'person_email' => $seatNumberArray['person_email'],
                     'person_phone' => $seatNumberArray['person_phone'],
                     'free' => 0,
                     'reserved' => 1,
                         ]);
            }
          }
         $this->redirect('this');
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
