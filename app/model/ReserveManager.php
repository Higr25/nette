<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Application\UI\Form;

class ReserveManager
{
  use Nette\SmartObject;

  private $database;


  public function __construct(Nette\Database\Context $database)
  {
      $this->database = $database;

  }

  public function reserve(Form $form, $values)
  {
    $reservePage = $form->getPresenter();
    $tableName = $reservePage->getParameter('tableName');

    $seatNumberInput = $values['seat_number'];
    $seatNumbers = explode(' ', $seatNumberInput);

      foreach ($seatNumbers as $seatNumber)
      {
        $row = $this->database->table($tableName)
               ->where('seat_number', $seatNumber)
               ->fetch();
        $isFree = $row['free'];

      if ($isFree)
      {
         $seatNumberArray = [
           'seat_number' => $seatNumber,
           'person' => $values['person'],
           'person_email' => $values['person_email'],
           'person_phone' => $values['person_phone'],
              ];

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
        $reservePage->redirect('Theatre:completed', [$seatNumbers, $tableName]);

        // echo '<pre>' , var_dump($tableName) , '</pre>';
        // die();



     }
}
