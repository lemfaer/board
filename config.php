<?php

return [

	"app" => [
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

	"routes" => [],
	"access" => []

];
