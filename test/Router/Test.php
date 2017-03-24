<?php

namespace Board\Test\Router;

use Board\Service\Config;
use Board\Service\Router;
use Board\Service\Container;

class Test {

	function test_dispatch_method() {
		$cont = new Container();
		$conf = $cont->get(Config::class);

		$conf["routes"] = [
			[ "GET", "~^/$~", [ MockA::class, "test1" ] ],
			[ "POST", "~^/$~", [ MockA::class, "test2" ] ]
		];

		$rout = $cont->get(Router::class);

		$res1 = $rout->dispatch("GET", "/");
		$res2 = $rout->dispatch("POST", "/");

		assert($res1 === "test1");
		assert($res2 === "test2");
	}

	function test_dispatch_uri() {
		$cont = new Container();
		$conf = $cont->get(Config::class);

		$conf["routes"] = [
			[ "GET", "~^/test1$~", [ MockA::class, "test1" ] ],
			[ "GET", "~^/test2$~", [ MockA::class, "test2" ] ]
		];

		$rout = $cont->get(Router::class);

		$res1 = $rout->dispatch("GET", "/test1");
		$res2 = $rout->dispatch("GET", "/test2");

		assert($res1 === "test1");
		assert($res2 === "test2");
	}

	function test_dispatch_uri_params() {
		$cont = new Container();
		$conf = $cont->get(Config::class);

		$conf["routes"] = [
			[ "GET", "~^/(\d+)/(\d+)$~", [ MockA::class, "add" ] ],
			[ "GET", "~^/(\d+)/(\d+)/(\d+)$~", [ MockA::class, "mul" ] ]
		];

		$rout = $cont->get(Router::class);

		$res1 = $rout->dispatch("GET", "/1/2");
		$res2 = $rout->dispatch("GET", "/1/2/3");

		assert($res1 === 3);
		assert($res2 === 6);
	}

}
