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

      $form->onSuccess[] = [$this, 'addEvent'];

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

    public function addEvent(form $form, $values)
    {

      $date = $values->date;
      $time = $values->time;
      $name = $values->name;
      $price = $values->price;
      $datetime = new \DateTime($date . ' ' . $time);
      $tableName = $values->name;
      $tableName = str_replace(' ','_', $tableName);

      $replace = [
    '&lt;' => '', '&gt;' => '', '&#039;' => '', '&amp;' => '',
    '&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae',
    '&Auml;' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
    'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D',
    'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E',
    'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G',
    'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I',
    'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I',
    'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'K', 'Ľ' => 'K',
    'Ĺ' => 'K', 'Ļ' => 'K', 'Ŀ' => 'K', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N',
    'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
    'Ö' => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
    'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S',
    'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T',
    'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U',
    '&Uuml;' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
    'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z',
    'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
    'ä' => 'ae', '&auml;' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
    'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
    'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
    'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
    'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h',
    'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i',
    'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j',
    'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l',
    'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n',
    'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
    '&ouml;' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe',
    'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
    'û' => 'u', 'ü' => 'ue', 'ū' => 'u', '&uuml;' => 'ue', 'ů' => 'u', 'ű' => 'u',
    'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y',
    'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss',
    'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
    'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
    'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
    'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
    'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
    'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
    'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
    'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
    'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
    'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
    'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
    'ю' => 'yu', 'я' => 'ya'
];


    $tableName = str_replace(array_keys($replace), $replace, $tableName);
    $tableName = strtolower($tableName);


    $this->database->table('schedule')->
    insert([
        'date' => $date,
        'time' => $time,
        'name' => $name,
        'price' => $price,
        'table_name' => $tableName,
        'datetime' => $datetime
    ]);

        $this->database->query("
        CREATE TABLE `$tableName` (
        `seat_number` int(10) NOT NULL AUTO_INCREMENT,
        `free` tinyint(1) NOT NULL DEFAULT '1',
        `reserved` tinyint(1) DEFAULT '0',
        `sold` tinyint(1) DEFAULT '0',
        `person` varchar(255) DEFAULT NULL,
        `person_phone` int(20) DEFAULT NULL,
        `person_email` varchar(255) DEFAULT NULL,
        PRIMARY KEY (`seat_number`)
        ) ENGINE=InnoDB AUTO_INCREMENT=173 DEFAULT CHARSET=latin1;

        INSERT INTO `$tableName`(`seat_number`,`free`,`reserved`,`sold`,`person`,`person_phone`,`person_email`) values (1,1,0,0,NULL,NULL,NULL),(2,1,0,0,NULL,NULL,NULL),(3,1,0,0,NULL,NULL,NULL),(4,1,0,0,NULL,NULL,NULL),(5,1,0,0,NULL,NULL,NULL),(6,1,0,0,NULL,NULL,NULL),(7,1,0,0,NULL,NULL,NULL),(8,1,0,0,NULL,NULL,NULL),(9,1,0,0,NULL,NULL,NULL),(10,1,0,0,NULL,NULL,NULL),(11,1,0,0,NULL,NULL,NULL),(12,1,0,0,NULL,NULL,NULL),(13,1,0,0,NULL,NULL,NULL),(14,1,0,0,NULL,NULL,NULL),(15,1,0,0,NULL,NULL,NULL),(16,1,0,0,NULL,NULL,NULL),(17,1,0,0,NULL,NULL,NULL),(18,1,0,0,NULL,NULL,NULL),(19,1,0,0,NULL,NULL,NULL),(20,1,0,0,NULL,NULL,NULL),(21,1,0,0,NULL,NULL,NULL),(22,1,0,0,NULL,NULL,NULL),(23,1,0,0,NULL,NULL,NULL),(24,1,0,0,NULL,NULL,NULL),(25,1,0,0,NULL,NULL,NULL),(26,1,0,0,NULL,NULL,NULL),(27,1,0,0,NULL,NULL,NULL),(28,1,0,0,NULL,NULL,NULL),(29,1,0,0,NULL,NULL,NULL),(30,1,0,0,NULL,NULL,NULL),(31,1,0,0,NULL,NULL,NULL),(32,1,0,0,NULL,NULL,NULL),(33,1,0,0,NULL,NULL,NULL),(34,1,0,0,NULL,NULL,NULL),(35,1,0,0,NULL,NULL,NULL),(36,1,0,0,NULL,NULL,NULL),(37,1,0,0,NULL,NULL,NULL),(38,1,0,0,NULL,NULL,NULL),(39,1,0,0,NULL,NULL,NULL),(40,1,0,0,NULL,NULL,NULL),(41,1,0,0,NULL,NULL,NULL),(42,1,0,0,NULL,NULL,NULL),(43,1,0,0,NULL,NULL,NULL),(44,1,0,0,NULL,NULL,NULL),(45,1,0,0,NULL,NULL,NULL),(46,1,0,0,NULL,NULL,NULL),(47,1,0,0,NULL,NULL,NULL),(48,1,0,0,NULL,NULL,NULL),(49,1,0,0,NULL,NULL,NULL),(50,1,0,0,NULL,NULL,NULL),(51,1,0,0,NULL,NULL,NULL),(52,1,0,0,NULL,NULL,NULL),(53,1,0,0,NULL,NULL,NULL),(54,1,0,0,NULL,NULL,NULL),(55,1,0,0,NULL,NULL,NULL),(56,1,0,0,NULL,NULL,NULL),(57,1,0,0,NULL,NULL,NULL),(58,1,0,0,NULL,NULL,NULL),(59,1,0,0,NULL,NULL,NULL),(60,1,0,0,NULL,NULL,NULL),(61,1,0,0,NULL,NULL,NULL),(62,1,0,0,NULL,NULL,NULL),(63,1,0,0,NULL,NULL,NULL),(64,1,0,0,NULL,NULL,NULL),(65,1,0,0,NULL,NULL,NULL),(66,1,0,0,NULL,NULL,NULL),(67,1,0,0,NULL,NULL,NULL),(68,1,0,0,NULL,NULL,NULL),(69,1,0,0,NULL,NULL,NULL),(70,1,0,0,NULL,NULL,NULL),(71,1,0,0,NULL,NULL,NULL),(72,1,0,0,NULL,NULL,NULL),(73,1,0,0,NULL,NULL,NULL),(74,1,0,0,NULL,NULL,NULL),(75,1,0,0,NULL,NULL,NULL),(76,1,0,0,NULL,NULL,NULL),(77,1,0,0,NULL,NULL,NULL),(78,1,0,0,NULL,NULL,NULL),(79,1,0,0,NULL,NULL,NULL),(80,1,0,0,NULL,NULL,NULL),(81,1,0,0,NULL,NULL,NULL),(82,1,0,0,NULL,NULL,NULL),(83,1,0,0,NULL,NULL,NULL),(84,1,0,0,NULL,NULL,NULL),(85,1,0,0,NULL,NULL,NULL),(86,1,0,0,NULL,NULL,NULL),(87,1,0,0,NULL,NULL,NULL),(88,1,0,0,NULL,NULL,NULL),(89,1,0,0,NULL,NULL,NULL),(90,1,0,0,NULL,NULL,NULL),(91,1,0,0,NULL,NULL,NULL),(92,1,0,0,NULL,NULL,NULL),(93,1,0,0,NULL,NULL,NULL),(94,1,0,0,NULL,NULL,NULL),(95,1,0,0,NULL,NULL,NULL),(96,1,0,0,NULL,NULL,NULL),(97,1,0,0,NULL,NULL,NULL),(98,1,0,0,NULL,NULL,NULL),(99,1,0,0,NULL,NULL,NULL),(100,1,0,0,NULL,NULL,NULL),(101,1,0,0,NULL,NULL,NULL),(102,1,0,0,NULL,NULL,NULL),(103,1,0,0,NULL,NULL,NULL),(104,1,0,0,NULL,NULL,NULL),(105,1,0,0,NULL,NULL,NULL),(106,1,0,0,NULL,NULL,NULL),(107,1,0,0,NULL,NULL,NULL),(108,1,0,0,NULL,NULL,NULL),(109,1,0,0,NULL,NULL,NULL),(110,1,0,0,NULL,NULL,NULL),(111,1,0,0,NULL,NULL,NULL),(112,1,0,0,NULL,NULL,NULL),(113,1,0,0,NULL,NULL,NULL),(114,1,0,0,NULL,NULL,NULL),(115,1,0,0,NULL,NULL,NULL),(116,1,0,0,NULL,NULL,NULL),(117,1,0,0,NULL,NULL,NULL),(118,1,0,0,NULL,NULL,NULL),(119,1,0,0,NULL,NULL,NULL),(120,1,0,0,NULL,NULL,NULL),(121,1,0,0,NULL,NULL,NULL),(122,1,0,0,NULL,NULL,NULL),(123,1,0,0,NULL,NULL,NULL),(124,1,0,0,NULL,NULL,NULL),(125,1,0,0,NULL,NULL,NULL),(126,1,0,0,NULL,NULL,NULL),(127,1,0,0,NULL,NULL,NULL),(128,1,0,0,NULL,NULL,NULL),(129,1,0,0,NULL,NULL,NULL),(130,1,0,0,NULL,NULL,NULL),(131,1,0,0,NULL,NULL,NULL),(132,1,0,0,NULL,NULL,NULL),(133,1,0,0,NULL,NULL,NULL),(134,1,0,0,NULL,NULL,NULL),(135,1,0,0,NULL,NULL,NULL),(136,1,0,0,NULL,NULL,NULL),(137,1,0,0,NULL,NULL,NULL),(138,1,0,0,NULL,NULL,NULL),(139,1,0,0,NULL,NULL,NULL),(140,1,0,0,NULL,NULL,NULL),(141,1,0,0,NULL,NULL,NULL),(142,1,0,0,NULL,NULL,NULL),(143,1,0,0,NULL,NULL,NULL),(144,1,0,0,NULL,NULL,NULL),(145,1,0,0,NULL,NULL,NULL),(146,1,0,0,NULL,NULL,NULL),(147,1,0,0,NULL,NULL,NULL),(148,1,0,0,NULL,NULL,NULL),(149,1,0,0,NULL,NULL,NULL),(150,1,0,0,NULL,NULL,NULL),(151,1,0,0,NULL,NULL,NULL),(152,1,0,0,NULL,NULL,NULL),(153,1,0,0,NULL,NULL,NULL),(154,1,0,0,NULL,NULL,NULL),(155,1,0,0,NULL,NULL,NULL),(156,1,0,0,NULL,NULL,NULL),(157,1,0,0,NULL,NULL,NULL),(158,1,0,0,NULL,NULL,NULL),(159,1,0,0,NULL,NULL,NULL),(160,1,0,0,NULL,NULL,NULL),(161,1,0,0,NULL,NULL,NULL),(162,1,0,0,NULL,NULL,NULL),(163,1,0,0,NULL,NULL,NULL),(164,1,0,0,NULL,NULL,NULL),(165,1,0,0,NULL,NULL,NULL),(166,1,0,0,NULL,NULL,NULL),(167,1,0,0,NULL,NULL,NULL),(168,1,0,0,NULL,NULL,NULL),(169,1,0,0,NULL,NULL,NULL),(170,1,0,0,NULL,NULL,NULL),(171,1,0,0,NULL,NULL,NULL),(172,1,0,0,NULL,NULL,NULL);
        ");
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
