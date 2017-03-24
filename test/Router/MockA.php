<?php

namespace Board\Test\Router;

class MockA {

	function test1() {
		return "test1";
	}

	function test2() {
		return "test2";
	}

	function add(...$vals) {
		return array_sum($vals);
	}

	function mul(...$vals) {
		return array_product($vals);
	}

}

