<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;


final class HistoryPresenter extends Nette\Application\UI\Presenter
{

    private $database;
    public function __construct(Nette\Database\Context $database)
    {
      $this->database = $database;
    }

    public function renderShow()
      {
        $date = new \DateTime();
        $date->sub(new \DateInterval('P30D'));
        $this->template->employeeReports = $this->database->table('employee_report')
             ->order('created_at DESC')
             ->where('created_at > ?', $date);

      }
}
