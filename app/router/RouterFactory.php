<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		$router->addRoute('form', 'Form:default');
		$router->addRoute('edit', 'Form:edit');
		$router->addRoute('history', 'History:show');
		$router->addRoute('logout', 'Login:out');
		$router->addRoute('login', 'Login:login');
		$router->addRoute('register', 'Register:default');
		$router->addRoute('email', 'Email:email');
		$router->addRoute('sendemail', 'Email:SendEmail');
		$router->addRoute('theatre/homepage', 'Theatre:homepage');
		$router->addRoute('theatre/schedule', 'Schedule:schedule');
		$router->addRoute('theatre/reserve', 'Theatre:reserve');
		$router->addRoute('theatre/out', 'Theatre:out');
		$router->addRoute('schedule/out', 'Schedule:out');
		$router->addRoute('schedule/delete', 'Schedule:delete');
		$router->addRoute('theatre/completed', 'Completed:completed');

		return $router;
	}


}
