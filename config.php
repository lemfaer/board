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
			PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"
		]
	],

	"pass" => "/^[\w~\!@#\$%\^&\*_\-\+\=`\|\(\)\{\}\[\]\:;\"'\<\>,\.\?]{6,20}$/",

	"routes" => [
		[ "GET",  "~^employee$~", [ \Board\Controller\Employee::class, "list" ] ],
		[ "GET",  "~^employee/create$~", [ \Board\Controller\Employee::class, "create" ] ],
		[ "POST", "~^employee/create$~", [ \Board\Controller\Employee::class, "create" ] ],
		[ "GET",  "~^employee/update/([0-9]+)$~", [ \Board\Controller\Employee::class, "update" ] ],
		[ "POST", "~^employee/update/([0-9]+)$~", [ \Board\Controller\Employee::class, "update" ] ],
		[ "GET",  "~^employee/delete/([0-9]+)$~", [ \Board\Controller\Employee::class, "delete" ] ],
		[ "POST", "~^employee/delete/([0-9]+)$~", [ \Board\Controller\Employee::class, "delete" ] ],
	],

	"access" => [
		"all" => -1,

		"employee.view" => 1 << 0,
		"employee.create" => 1 << 1,
		"employee.update" => 1 << 2,
		"employee.delete" => 1 << 3,
	],

	"messages" => [
		"employee.email" => "Email is invalid",
		"employee.pass" => "Password must contain 6-20 alphanumeric characters or symbols.",
		"employee.created" => "Employee was successfully created",
		"employee.updated" => "Employee was successfully updated",
		"employee.deleted" => "Employee was successfully deleted"
	]

];
