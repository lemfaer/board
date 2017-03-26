<?php

require __DIR__ . "/inc/autoload.php";
require __DIR__ . "/src/autoload.php";
require __DIR__ . "/test/autoload.php";

if (intval(ini_get("zend.assertions")) < 1) {
	print("zend.assertions isn't set to 1, please check php.ini\n");
	die;
}

ini_set("assert.active", 1);
ini_set("assert.exception", 1);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

$tests = [
	\Board\Test\Container\Test::class => [
		"test_get_object", "test_get_object_dep", "test_get_object_same", "test_get_value"
	],
	\Board\Test\Router\Test::class => [
		"test_dispatch_method", "test_dispatch_uri", "test_dispatch_uri_params"
	],
	\Board\Test\View\Test::class => [
		"test_view", "test_view_params"
	],
	\Board\Test\Session\Test::class => [
		"test_array_set", "test_array_set_key", "test_array_isset", "test_array_unset",
		"test_iterator", "test_count"
	],
	\Board\Test\Message\Test::class => [
		"test_message", "test_pop_all", "test_flush"
	],
	\Board\Test\Auth\Test::class => [
		"test_login", "test_logout", "test_logged", "test_access"
	]
];

foreach ($tests as $cls => $meths) {
	$obj = new $cls();
	foreach ($meths as $meth) {
		call_user_func([ $obj, $meth ]);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Test</title>
</head>
<body>
	<div class="tests">
		<?php foreach ($tests as $cls => $meths): ?>
			<div class="cls">
				<span class="title"><?= $cls ?></span>

				<?php foreach ($meths as $meth): ?>
					<div class="meth" style="margin-left: 20px">
						<span class="title"><?= $meth ?></span>
						<span class="status">passed</span>
					</div>
				<?php endforeach ?>
			</div>
		<?php endforeach ?>
	</div>
	<div class="status">All tests passed</div>
</body>
</html>
