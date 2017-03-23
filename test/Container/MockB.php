<?php

namespace Board\Test\Container;

class MockB {

	public $mock_a;

	function __construct(MockA $mock_a) {
		$this->mock_a = $mock_a;
	}

}
