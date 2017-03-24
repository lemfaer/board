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

	function test_get_object_same() {
		$con = new Container();

		$o1 = $con->get(MockA::class);
		$o2 = $con->get(MockA::class);

		assert($o1 === $o2);
	}

	function test_get_value() {
		$con = new Container();
		$con["key"] = "val";
		$val = $con->get("key");
		assert($val === "val");
	}

}
