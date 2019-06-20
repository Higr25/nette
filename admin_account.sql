/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.5.5-10.1.29-MariaDB 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `users` (
	`id` int ,
	`username` varchar ,
	`first_name` varchar ,
	`last_name` varchar ,
	`password` varchar ,
	`email` varchar ,
	`role` varchar 
); 
insert into `users` (`id`, `username`, `first_name`, `last_name`, `password`, `email`, `role`) values('2','admin','admin','admin','$2y$10$f8NkSxOCLVhlGZQqUQZKEOySmyGEy7Z/kR7nODOEjPt8q6rjg.GQ2','','admin');
