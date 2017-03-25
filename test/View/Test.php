<?php

namespace Board\Test\View;

use Board\Service\View;
use Board\Service\Config;
use Board\Service\Container;

class Test {

	function test_view() {
		$cont = new Container();

		$conf = $cont->get(Config::class);
		$conf["app"]["view"] = __DIR__;

		$view = $cont->get(View::class);
		$res = $view("view");
		$res = trim($res);

		assert($res === "test1");
	}

	function test_view_params() {
		$cont = new Container();

		$conf = $cont->get(Config::class);
		$conf["app"]["view"] = __DIR__;

		$view = $cont->get(View::class);
		$res = $view("view_params", [ "param" => "test2" ]);
		$res = trim($res);

		assert($res === "test2");
	}

}
