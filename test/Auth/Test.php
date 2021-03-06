<?php

namespace Board\Test\Auth;

use Board\Service\Auth;
use Board\Service\Config;
use Board\Service\Container;
use Board\Service\Connection;
use Board\Exception\AccessDenied;

class Test {

	function test_login() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		session_unset();

		$cont = new Container();
		$cont[Connection::class] = new MockC();
		$auth = $cont->get(Auth::class);

		$empl = new MockA();
		$auth->login($empl);

		assert($_SESSION["auth"] === 1);
	}

	function test_logout() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		session_unset();

		$cont = new Container();
		$cont[Connection::class] = new MockC();
		$auth = $cont->get(Auth::class);

		$empl = new MockA();
		$auth->login($empl);
		$auth->logout();

		assert(isset($_SESSION["auth"]) === false);
	}

	function test_logged() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		session_unset();

		$cont = new Container();
		$cont[Connection::class] = new MockC();
		$auth = $cont->get(Auth::class);

		$empl = new MockA();
		$auth->login($empl);
		$res = $auth->logged();

		assert($res === true);
	}

	function test_require() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		session_unset();

		$cont = new Container();
		$cont[Connection::class] = new MockC();
		$auth = $cont->get(Auth::class);
		$conf = $cont->get(Config::class);

		$conf["anonym"] = 0;
		$conf["access"]["test"] = (1 << 1) | (1 << 2);

		try {
			$emp1 = new MockA();
			$auth->login($emp1);
			$auth->require("test");
			$res1 = true;
		} catch (AccessDenied $e) {
			$res1 = false;
		}

		try {
			$emp2 = new MockB();
			$auth->login($emp2);
			$auth->require("test");
			$res2 = true;
		} catch (AccessDenied $e) {
			$res2 = false;
		}

		assert($res1 === true);
		assert($res2 === false);
	}

}
