<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Application\UI\Form;

class TheatreManager
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

    $params = [
      'price' => $values['price'],
      'email' => $values['person_email'],
      'person' => $values['person'],
      'tableName' => $tableName,
      'seatNumbers' => $values['seat_number']
    ];

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


        $reservePage->redirect('Completed:completed', [$params]);

        // echo '<pre>' , var_dump($tableName) , '</pre>';
        // die();



     }

     public function addEvent(form $form, $values)
     {
       $schedulePage = $form->getPresenter();
       $date = $values->date;
       $time = $values->time;
       $name = $values->name;
       $price = $values->price;
       $datetime = new \DateTime($date . ' ' . $time);
       $tableNameDB = $values->name;
       $tableNameDB = str_replace(' ','_', $tableNameDB);

       $replace = [
             'Á' => 'A','Č' => 'C', 'Ď' => 'D', 'É' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Í' => 'I', 'Ň' => 'N',
             'Ó' => 'O', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ú' => 'U', 'Ů' => 'U', 'Ý' => 'Y', 'Ž' => 'Z',
             'á' => 'a','č' => 'c', 'ď' => 'd', 'é' => 'e', 'ě' => 'e', 'í' => 'i', 'ň' => 'n', 'ó' => 'o',
             'ř' => 'r', 'š' => 's', 'ú' => 'u', 'ů' => 'u', 'ý' => 'y', 'ž' => 'z',
           ];

     $tableNameDB = str_replace(array_keys($replace), $replace, $tableNameDB);
     $tableNameDB = strtolower($tableNameDB);

     $tableRow = $this->database->table('schedule')
                                ->where('table_name', $tableNameDB)
                                ->fetch();
     $tableVerify = $tableRow['table_name'];

     $i = 1;
     if ($tableVerify == $tableNameDB)
     {
       $tableNameDB = $tableNameDB . '_' . $i;

       $tableRow = $this->database->table('schedule')
                                  ->where('table_name', $tableNameDB)
                                  ->fetch();
       $tableVerify = $tableRow['table_name'];
       $i++;

       while ($tableVerify == $tableNameDB) {
         $substr = substr($tableNameDB, -1);
         $tableNameDB = str_replace($substr, $i, $tableNameDB);
         $i++;

         $tableRow = $this->database->table('schedule')
                                    ->where('table_name', $tableNameDB)
                                    ->fetch();
         $tableVerify = $tableRow['table_name'];
       }
     }

     $this->database->table('schedule')
                    ->insert([
                         'date' => $date,
                         'time' => $time,
                         'name' => $name,
                         'price' => $price,
                         'table_name' => $tableNameDB,
                         'datetime' => $datetime
                     ]);

         $this->database->query("
         CREATE TABLE `$tableNameDB` (
         `seat_number` int(10) NOT NULL AUTO_INCREMENT,
         `free` tinyint(1) NOT NULL DEFAULT '1',
         `reserved` tinyint(1) DEFAULT '0',
         `sold` tinyint(1) DEFAULT '0',
         `person` varchar(255) DEFAULT NULL,
         `person_phone` int(20) DEFAULT NULL,
         `person_email` varchar(255) DEFAULT NULL,
         PRIMARY KEY (`seat_number`)
         ) ENGINE=InnoDB AUTO_INCREMENT=173 DEFAULT CHARSET=latin1;

         INSERT INTO `$tableNameDB`(`seat_number`,`free`,`reserved`,`sold`,`person`,`person_phone`,`person_email`) values (1,1,0,0,NULL,NULL,NULL),(2,1,0,0,NULL,NULL,NULL),(3,1,0,0,NULL,NULL,NULL),(4,1,0,0,NULL,NULL,NULL),(5,1,0,0,NULL,NULL,NULL),(6,1,0,0,NULL,NULL,NULL),(7,1,0,0,NULL,NULL,NULL),(8,1,0,0,NULL,NULL,NULL),(9,1,0,0,NULL,NULL,NULL),(10,1,0,0,NULL,NULL,NULL),(11,1,0,0,NULL,NULL,NULL),(12,1,0,0,NULL,NULL,NULL),(13,1,0,0,NULL,NULL,NULL),(14,1,0,0,NULL,NULL,NULL),(15,1,0,0,NULL,NULL,NULL),(16,1,0,0,NULL,NULL,NULL),(17,1,0,0,NULL,NULL,NULL),(18,1,0,0,NULL,NULL,NULL),(19,1,0,0,NULL,NULL,NULL),(20,1,0,0,NULL,NULL,NULL),(21,1,0,0,NULL,NULL,NULL),(22,1,0,0,NULL,NULL,NULL),(23,1,0,0,NULL,NULL,NULL),(24,1,0,0,NULL,NULL,NULL),(25,1,0,0,NULL,NULL,NULL),(26,1,0,0,NULL,NULL,NULL),(27,1,0,0,NULL,NULL,NULL),(28,1,0,0,NULL,NULL,NULL),(29,1,0,0,NULL,NULL,NULL),(30,1,0,0,NULL,NULL,NULL),(31,1,0,0,NULL,NULL,NULL),(32,1,0,0,NULL,NULL,NULL),(33,1,0,0,NULL,NULL,NULL),(34,1,0,0,NULL,NULL,NULL),(35,1,0,0,NULL,NULL,NULL),(36,1,0,0,NULL,NULL,NULL),(37,1,0,0,NULL,NULL,NULL),(38,1,0,0,NULL,NULL,NULL),(39,1,0,0,NULL,NULL,NULL),(40,1,0,0,NULL,NULL,NULL),(41,1,0,0,NULL,NULL,NULL),(42,1,0,0,NULL,NULL,NULL),(43,1,0,0,NULL,NULL,NULL),(44,1,0,0,NULL,NULL,NULL),(45,1,0,0,NULL,NULL,NULL),(46,1,0,0,NULL,NULL,NULL),(47,1,0,0,NULL,NULL,NULL),(48,1,0,0,NULL,NULL,NULL),(49,1,0,0,NULL,NULL,NULL),(50,1,0,0,NULL,NULL,NULL),(51,1,0,0,NULL,NULL,NULL),(52,1,0,0,NULL,NULL,NULL),(53,1,0,0,NULL,NULL,NULL),(54,1,0,0,NULL,NULL,NULL),(55,1,0,0,NULL,NULL,NULL),(56,1,0,0,NULL,NULL,NULL),(57,1,0,0,NULL,NULL,NULL),(58,1,0,0,NULL,NULL,NULL),(59,1,0,0,NULL,NULL,NULL),(60,1,0,0,NULL,NULL,NULL),(61,1,0,0,NULL,NULL,NULL),(62,1,0,0,NULL,NULL,NULL),(63,1,0,0,NULL,NULL,NULL),(64,1,0,0,NULL,NULL,NULL),(65,1,0,0,NULL,NULL,NULL),(66,1,0,0,NULL,NULL,NULL),(67,1,0,0,NULL,NULL,NULL),(68,1,0,0,NULL,NULL,NULL),(69,1,0,0,NULL,NULL,NULL),(70,1,0,0,NULL,NULL,NULL),(71,1,0,0,NULL,NULL,NULL),(72,1,0,0,NULL,NULL,NULL),(73,1,0,0,NULL,NULL,NULL),(74,1,0,0,NULL,NULL,NULL),(75,1,0,0,NULL,NULL,NULL),(76,1,0,0,NULL,NULL,NULL),(77,1,0,0,NULL,NULL,NULL),(78,1,0,0,NULL,NULL,NULL),(79,1,0,0,NULL,NULL,NULL),(80,1,0,0,NULL,NULL,NULL),(81,1,0,0,NULL,NULL,NULL),(82,1,0,0,NULL,NULL,NULL),(83,1,0,0,NULL,NULL,NULL),(84,1,0,0,NULL,NULL,NULL),(85,1,0,0,NULL,NULL,NULL),(86,1,0,0,NULL,NULL,NULL),(87,1,0,0,NULL,NULL,NULL),(88,1,0,0,NULL,NULL,NULL),(89,1,0,0,NULL,NULL,NULL),(90,1,0,0,NULL,NULL,NULL),(91,1,0,0,NULL,NULL,NULL),(92,1,0,0,NULL,NULL,NULL),(93,1,0,0,NULL,NULL,NULL),(94,1,0,0,NULL,NULL,NULL),(95,1,0,0,NULL,NULL,NULL),(96,1,0,0,NULL,NULL,NULL),(97,1,0,0,NULL,NULL,NULL),(98,1,0,0,NULL,NULL,NULL),(99,1,0,0,NULL,NULL,NULL),(100,1,0,0,NULL,NULL,NULL),(101,1,0,0,NULL,NULL,NULL),(102,1,0,0,NULL,NULL,NULL),(103,1,0,0,NULL,NULL,NULL),(104,1,0,0,NULL,NULL,NULL),(105,1,0,0,NULL,NULL,NULL),(106,1,0,0,NULL,NULL,NULL),(107,1,0,0,NULL,NULL,NULL),(108,1,0,0,NULL,NULL,NULL),(109,1,0,0,NULL,NULL,NULL),(110,1,0,0,NULL,NULL,NULL),(111,1,0,0,NULL,NULL,NULL),(112,1,0,0,NULL,NULL,NULL),(113,1,0,0,NULL,NULL,NULL),(114,1,0,0,NULL,NULL,NULL),(115,1,0,0,NULL,NULL,NULL),(116,1,0,0,NULL,NULL,NULL),(117,1,0,0,NULL,NULL,NULL),(118,1,0,0,NULL,NULL,NULL),(119,1,0,0,NULL,NULL,NULL),(120,1,0,0,NULL,NULL,NULL),(121,1,0,0,NULL,NULL,NULL),(122,1,0,0,NULL,NULL,NULL),(123,1,0,0,NULL,NULL,NULL),(124,1,0,0,NULL,NULL,NULL),(125,1,0,0,NULL,NULL,NULL),(126,1,0,0,NULL,NULL,NULL),(127,1,0,0,NULL,NULL,NULL),(128,1,0,0,NULL,NULL,NULL),(129,1,0,0,NULL,NULL,NULL),(130,1,0,0,NULL,NULL,NULL),(131,1,0,0,NULL,NULL,NULL),(132,1,0,0,NULL,NULL,NULL),(133,1,0,0,NULL,NULL,NULL),(134,1,0,0,NULL,NULL,NULL),(135,1,0,0,NULL,NULL,NULL),(136,1,0,0,NULL,NULL,NULL),(137,1,0,0,NULL,NULL,NULL),(138,1,0,0,NULL,NULL,NULL),(139,1,0,0,NULL,NULL,NULL),(140,1,0,0,NULL,NULL,NULL),(141,1,0,0,NULL,NULL,NULL),(142,1,0,0,NULL,NULL,NULL),(143,1,0,0,NULL,NULL,NULL),(144,1,0,0,NULL,NULL,NULL),(145,1,0,0,NULL,NULL,NULL),(146,1,0,0,NULL,NULL,NULL),(147,1,0,0,NULL,NULL,NULL),(148,1,0,0,NULL,NULL,NULL),(149,1,0,0,NULL,NULL,NULL),(150,1,0,0,NULL,NULL,NULL),(151,1,0,0,NULL,NULL,NULL),(152,1,0,0,NULL,NULL,NULL),(153,1,0,0,NULL,NULL,NULL),(154,1,0,0,NULL,NULL,NULL),(155,1,0,0,NULL,NULL,NULL),(156,1,0,0,NULL,NULL,NULL),(157,1,0,0,NULL,NULL,NULL),(158,1,0,0,NULL,NULL,NULL),(159,1,0,0,NULL,NULL,NULL),(160,1,0,0,NULL,NULL,NULL),(161,1,0,0,NULL,NULL,NULL),(162,1,0,0,NULL,NULL,NULL),(163,1,0,0,NULL,NULL,NULL),(164,1,0,0,NULL,NULL,NULL),(165,1,0,0,NULL,NULL,NULL),(166,1,0,0,NULL,NULL,NULL),(167,1,0,0,NULL,NULL,NULL),(168,1,0,0,NULL,NULL,NULL),(169,1,0,0,NULL,NULL,NULL),(170,1,0,0,NULL,NULL,NULL),(171,1,0,0,NULL,NULL,NULL),(172,1,0,0,NULL,NULL,NULL);
         ");

         $schedulePage->redirect('Schedule:schedule', [$tableNameDB]);
     }
}
