<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Model\UserManager;

final class RegisterPresenter extends Nette\Application\UI\Presenter
{
    private $database;
    private $userManager;

    public function __construct(UserManager $userManager, Nette\Database\Context $database)
    {
      $this->userManager = $userManager;
      $this->database = $database;
    }


    public function createComponentRegisterForm(): Form
    {
        $form = new Form;

        $form->addText('username', 'Username:')
             ->setRequired();

        $form->addText('first_name', 'First Name:')
             ->setRequired();

        $form->addText('last_name', 'Last Name:')
             ->setRequired();

        $form->addText('password', 'Password:')
             ->setRequired();

        $form->addSubmit('register', 'Register')
             ->getControlPrototype()
             ->addClass('btn btn-primary');


        $form->onSuccess[] = [$this, 'register'];

        return $form;
    }

    public function register(Form $form, \stdClass $values)
    {

        $username = $values->username;
        $firstName = $values->first_name;
        $lastName = $values->last_name;
        $password = $values->password;


        $this->userManager->add($username, $firstName, $lastName, $password);

        $this->flashMessage('Uživatel úspěšně zaregistrován', 'success');

        $this->redirect('this');
    }
}
