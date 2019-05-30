Working on a new Theatre project. Barely usable.
-

Using users.sql, view.sql, schedule.sql, hloupy_honza.sql, revizor.sql and prodana_nevesta.sql

Homepage: nette/www/theatre/homepage

Schedule: nette/www/theatre/schedule

Reservation: nette/www/theatre/reservation

--------------------

Database connection in local.neon file:



	database:

		dsn: 'mysql:host=127.0.0.1;dbname=test'  
	
		user: root  
	
		password:  
	
  --------------------
  
  Use employee_report.sql to create essential table and users.sql to create login database.
  
  --------------------
  
  Login: nette/www/login
  
  Creating report: nette/www/form
  
  Report History & Edit: nette/www/history
