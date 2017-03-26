<?php

namespace Board\Test\Auth;

use Board\Model\Employee;

class MockB extends Employee {

	public $id = 2;
	public $access = 0;

	public function __construct() {}

}
