Working on a new Theatre project. Already usable, not completed.
-

Use admin_user.sql, view.sql, schedule.sql, hloupy_honza.sql, revizor.sql and prodana_nevesta.sql

Use admin account to unlock admin features in Schedule and Reservation.

Login: admin
Password: admin   


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
  
  Employee report project:
  -
  
  Use employee_report.sql to create essential table and users.sql to create login database.
  
  --------------------
  
  Login: nette/www/login
  
  Creating report: nette/www/form
  
  Report History & Edit: nette/www/history
