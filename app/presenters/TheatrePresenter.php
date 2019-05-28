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
         $this->template->seatsFloor = $this->database->table('view')
                                      ->where('seat_number <= ?', 120);
         $this->template->seatsBalconyLeft = $this->database->table('view')
                                            ->where('seat_number >= ? AND seat_number <= ?', 121, 146);
         $this->template->seatsBalconyRight = $this->database->table('view')
                                              ->where('seat_number >= ? AND seat_number <= ?', 147, 172);
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


        $form->onSuccess[] = [$this, 'reserve'];

        return $form;
    }

    public function reserve(Form $form, $values)
    {
      $seatNumberInput = $values['seat_number'];
      $seatNumbers = explode(' ', $seatNumberInput);

        foreach ($seatNumbers as $seatNumber) {
          $row = $this->database->table('view')
                 ->where('seat_number', $seatNumber)
                 ->fetch();
          $isFree = $row['free'];

          // echo '<pre>' , var_dump($seatNumbers) , '</pre>';
          // die();

        if ($isFree) {

           $seatNumberArray = [
             'seat_number' => $seatNumber,
             'person' => $values['person'],
             'person_email' => $values['person_email'],
             'person_phone' => $values['person_phone'],
             'free' => 0,
             'reserved' => 1];

           $seat = $this->database->table('view')
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

}
