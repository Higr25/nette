<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;

final class ViewPresenter extends Nette\Application\UI\Presenter
{
  protected function createComponentEmployeeForm(): Form
  {
    $form = new Form;


     $form->addText('date', 'Datum:')
          ->setRequired();

     $form->addText('work_start', 'Začátek Práce:')
          ->setRequired();

     $form->addText('work_end', 'Konec Práce:')
          ->setRequired();

     $form->addTextArea('comment', 'Náplň Práce:')
          ->setRequired()
          ->getControlPrototype()
          ->addClass('comment');

     $form->addSubmit('submit', 'Odeslat')
          ->getControlPrototype()
          ->addClass('btn btn-primary');


     $form->onSuccess[] = [$this, 'employeeFormSucceeded'];

     return $form;
}
