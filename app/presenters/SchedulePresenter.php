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


    public function renderSchedule(): void
    {
         $this->template->shows = $this->database->table('schedule');


    }
}
