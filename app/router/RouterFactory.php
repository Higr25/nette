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
		$router->addRoute('email', 'Email:default');
		$router->addRoute('sendemail', 'Email:SendEmail');
		$router->addRoute('view', 'View:default');
		$router->addRoute('theatre', 'Theatre:view');
		
		return $router;
	}
}
