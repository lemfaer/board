<?php

namespace Board\Test\Session;

use Board\Service\Session;
use Board\Service\Container;

class Test {

	function test_array_set() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		session_unset();

		$cont = new Container();
		$sess = $cont->get(Session::class);

		$sess[] = "test1";
		$res = array_shift($_SESSION);

		assert($res === "test1");
	}

	function test_array_set_key() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		session_unset();

		$cont = new Container();
		$sess = $cont->get(Session::class);

		$sess["test1"] = "test2";
		$res = $_SESSION["test1"];

		assert($res === "test2");
	}

	function test_array_isset() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		session_unset();

		$cont = new Container();
		$sess = $cont->get(Session::class);

		$sess["test1"] = "test2";

		assert(isset($sess["test1"]) === true);
		assert(isset($sess["test2"]) === false);
	}

	function test_array_unset() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		session_unset();

		$cont = new Container();
		$sess = $cont->get(Session::class);

		$sess["test1"] = "test2";
		unset($sess["test1"]);

		assert(isset($sess["test1"]) === false);
		assert(isset($_SESSION["test1"]) === false);
	}

	function test_iterator() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		session_unset();

		$cont = new Container();
		$sess = $cont->get(Session::class);

		$sess["test1"] = "test11";
		$sess["test2"] = "test22";
		$sess["test3"] = "test33";

		foreach ($sess as $key => $val) {
			assert($_SESSION[$key] === $val);
		}
	}

	function test_count() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		session_unset();

		$cont = new Container();
		$sess = $cont->get(Session::class);

		$sess["test1"] = "test11";
		$sess["test2"] = "test22";

		assert(count($_SESSION) === count($sess));
	}

}
