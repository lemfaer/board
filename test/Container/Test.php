<?php

namespace Board\Test\Container;

use Board\Service\Container;

class Test {

	function test_get_object() {
		$con = new Container();
		$obj = $con->get(MockA::class);
		assert($obj instanceof MockA);
	}

	function test_get_object_dep() {
		$con = new Container();

		$b = $con->get(MockB::class);
		$a = $b->mock_a;

		assert($a instanceof MockA);
		assert($b instanceof MockB);
	}

	function test_get_value() {
		$con = new Container();
		$con->set("test1", "test2");
		$val = $con->get("test1");
		assert($val === "test2");
	}

}
