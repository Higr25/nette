<?php

declare(strict_types=1);


namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;
use App\Model\UserManager;
use Nette\Security\Identity;



final class LoginPresenter extends Nette\Application\UI\Presenter
{

      private $user;

      private $userManager;

      public function __construct(User $user, UserManager $userManager)
      {
          $this->user = $user;
          $this->userManager = $userManager;
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


        public function register(Form $form, \stdClass $values)
        {

            $this->userManager->add($values->username, $values->password);

            $this->flashMessage('Uživatel úspěšně zaregistrován', 'success');
        }



        public function authenticate(form $form, \stdClass $values)
        {

          $credentials = [
            'username' => $values->username,
            'password' => $values->password,
          ];

          $this->getUser()->login($credentials['username'], $credentials['password']);

          $this->redirect('Form:default');
        }


      public function actionOut()
      {
          $this->getUser()->logout(true);
          $this->flashMessage('Odhlášení bylo úspěšné.', 'success');
          $this->redirect('Login:login');
      }

}
