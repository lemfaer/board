<?php

return [

	"app" => [
		"domain" => $_SERVER["HTTP_HOST"],
		"root" => __DIR__,
		"inc" => __DIR__ . "/inc",
		"src" => __DIR__ . "/src",
		"test" => __DIR__ . "/test",
		"view" => __DIR__ . "/view"
	],

	"db" => [
		"host" => "127.0.0.1",
		"base" => "board",
		"user" => "aroot",
		"pass" => "aroot",
		"options" => [
			\PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8",
			\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
		]
	],

	"time" => "H:i",

	"week" => [
		"num" => "N",
		"text" => "l"
	],

	"pass" => "/^[\w~\!@#\$%\^&\*_\-\+\=`\|\(\)\{\}\[\]\:;\"'\<\>,\.\?]{6,20}$/",

	"routes" => [
		[ "GET",  "~^testc$~", [ \Board\Controller\Test::class, "test" ] ],

		[ "GET", "~^$~", [ \Board\Controller\Board::class, "main" ] ],
		[ "GET", "~^room\-([0-9]+)/([0-9]{4}\-[0-9]{2})$~", [ \Board\Controller\Board::class, "main" ] ],

		[ "GET",  "~^login$~", [ \Board\Controller\Auth::class, "login" ] ],
		[ "POST", "~^login$~", [ \Board\Controller\Auth::class, "login_submit" ] ],
		[ "GET",  "~^logout$~", [ \Board\Controller\Auth::class, "logout" ] ],
		[ "POST", "~^logout$~", [ \Board\Controller\Auth::class, "logout" ] ],

		[ "GET",  "~^employee$~", [ \Board\Controller\Employee::class, "list" ] ],
		[ "GET",  "~^employee/create$~", [ \Board\Controller\Employee::class, "create" ] ],
		[ "POST", "~^employee/create$~", [ \Board\Controller\Employee::class, "create_submit" ] ],
		[ "GET",  "~^employee/update/([0-9]+)$~", [ \Board\Controller\Employee::class, "update" ] ],
		[ "POST", "~^employee/update/([0-9]+)$~", [ \Board\Controller\Employee::class, "update_submit" ] ],
		[ "GET",  "~^employee/delete/([0-9]+)$~", [ \Board\Controller\Employee::class, "delete" ] ],
		[ "POST", "~^employee/delete/([0-9]+)$~", [ \Board\Controller\Employee::class, "delete_submit" ] ],

		[ "GET",  "~^appointment/create$~", [ \Board\Controller\Appointment::class, "create" ] ],
		[ "POST", "~^appointment/create$~", [ \Board\Controller\Appointment::class, "create_submit" ] ],
		[ "GET",  "~^appointment/popup/day\-([0-9]{4}\-[0-9]{2}\-[0-9]{2})/type\-([a-z]+)/([0-9]+)$~", [ \Board\Controller\Appointment::class, "popup" ] ],
		[ "POST", "~^appointment/popup/day\-([0-9]{4}\-[0-9]{2}\-[0-9]{2})/type\-([a-z]+)/([0-9]+)$~", [ \Board\Controller\Appointment::class, "popup_submit" ] ],
	],

	"anonym" => -1,

	"access" => [
		"all" => -1,

		"employee.view" => 1 << 0,
		"employee.create" => 1 << 1,
		"employee.update" => 1 << 2,
		"employee.delete" => 1 << 3,

		"appointment.view" => 1 << 4,
		"appointment.create" => 1 << 5,
		"appointment.update" => 1 << 6,
		"appointment.delete" => 1 << 7
	],

	"messages" => [
		"auth.fail" => "Invalid email or password",

		"employee.email" => "Email is invalid",
		"employee.pass" => "Password must contain 6-20 alphanumeric characters or symbols.",
		"employee.created" => "Employee was successfully created",
		"employee.updated" => "Employee was successfully updated",
		"employee.deleted" => "Employee was successfully deleted",

		"appointment.day" => "Day end must be greater than day start",
		"appointment.time" => "Time end must be greater than time start",
		"appointment.created" => "Appointment was successfully created",
		"appointment.updated" => "Appointment was successfully updated",
		"appointment.deleted" => "Appointment was successfully deleted"
	],

	"intervals" => [
		"week" => new \DateInterval("P1W"),
		"bi-week" => new \DateInterval("P2W"),
		"month" => new \DateInterval("P4W")
	]

];
