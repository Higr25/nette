<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class TheatrePresenter extends Nette\Application\UI\Presenter
{
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }


    public function renderView(): void
    {
         $this->template->seats = $this->database->table('view');
    }

    public function createComponentReservationForm()
    {
        $form = new Form;

        $form->addText('seat_number', 'Číslo sedadla:')
             ->setRequired();

        $form->addText('person', 'Jméno a příjmení:');
             // ->setRequired();

        $form->addText('person_email', 'E-mail:');
             // ->setRequired();

        $form->addText('person_phone', 'Telefon:');
             // ->setRequired();

        $form->addSubmit('submit', 'Rezervovat');


        $form->onSuccess[] = [$this, 'reserve'];

        return $form;
    }

    public function reserve(Form $form, $values)
    {
      $seatNumber = $values['seat_number'];
      $row = $this->database->table('view')
            ->where('seat_number', $seatNumber)
            ->fetch();
      $isFree = $row['free'];

      $seatNumbers = explode(' ', $seatNumber);

      var_dump($seatNumbers);
      die();

      if ($isFree)
       {
         foreach ($seatNumbers as $seatNumber) {

           $seatNumber['person'] = $values['person'],
         }
        $seat = $this->database->table('view')
              ->where('seat_number', $values['seat_number'])
              ->update([
                  'person' => $values['person'],
                  'person_email' => $values['person_email'],
                  'person_phone' => $values['person_phone'],
                  'free' => 0,
                  'reserved' => 1,
                      ]);
      }
      else
      {
          $this->flashMessage('Sedadlo není volné', 'error');
      }
    }

    public function renderSelect(int $seatNumber)
    {

        $seat = $this->database->table('view')->get($seatNumber);
        $this->template->seats = $this->database->table('view');

        $seats[] = $seatNumber;

        $this['reservationForm']->setDefaults($seats);
    }
}
