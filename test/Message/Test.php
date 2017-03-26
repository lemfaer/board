<?php

namespace Board\Test\Message;

use Board\Service\Config;
use Board\Service\Message;
use Board\Service\Container;

class Test {

	function test_message() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		session_unset();

		$cont = new Container();
		$conf = $cont->get(Config::class);
		$mess = $cont->get(Message::class);

		$conf["messages"]["test1"] = "test2";
		$mess("test1");
		$res = array_shift($_SESSION["messages"]);

		assert($res === "test2");
	}

	function test_pop_all() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		session_unset();

		$cont = new Container();
		$conf = $cont->get(Config::class);
		$mess = $cont->get(Message::class);

		$conf["messages"]["test1"] = "test11";
		$conf["messages"]["test2"] = "test22";

		$mess("test1");
		$mess("test2");
		$mess->text("test33");

		$res = [];
		while ($mes = $mess->pop()) {
			$res[] = $mes;
		}

		assert(count($res) === 3);
		assert($res[0] === "test33");
		assert($res[1] === "test22");
		assert($res[2] === "test11");
	}

	function test_flush() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		session_unset();

		$cont = new Container();
		$mess = $cont->get(Message::class);

		$mess->text("test");
		$mess->text("test");
		$mess->text("test");
		$mess->flush();

		assert(isset($_SESSION["messages"]) === false);
	}

}
