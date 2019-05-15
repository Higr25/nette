<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Security\Passwords;
use Nette\Security\User;
use Nette\Security\IIdentity;

final class UserManager implements Nette\Security\IAuthenticator
  {
    use Nette\SmartObject;

    private $database;
    private $passwords;

    public function __construct(Nette\Database\Context $database, Passwords $passwords)

      {
        $this->database = $database;
        $this->passwords = $passwords;
      }

      public function authenticate(array $credentials): Nette\Security\IIdentity

        {

          [$username, $password] = $credentials;

          $row = $this->database->table('users')
              ->where('username', $username)
              ->fetch();

          if (!$row)
          {
            throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
          }

          elseif ($this->passwords->needsRehash($row['password']))
          {
            $row->update([
              'password' => $this->passwords->hash($password),
            ]);
          }

          elseif (!$this->passwords->verify($password, $row['password']))
          {
            throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
          }


             $arr = $row->toArray();
             unset($arr['password']);
             return new Nette\Security\Identity($row['id'], $row['role'], $arr);

        }


      public function add(string $username, string $firstName, string $lastName, string $password): void
        {
            try {
                $this->database->table('users')->insert([
                    'username' => $username,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'password' => $this->passwords->hash($password),
                  ]);
            }

              catch (Nette\Database\UniqueConstrainViolationException $e)
              {
                throw new DuplicateNameException;
              }
            }
        }




class DuplicateNameException extends \Exception
{

}
