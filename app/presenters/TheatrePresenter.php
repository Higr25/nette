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

    public function renderDialog()
    {
        
    }

}
