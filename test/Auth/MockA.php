<?php

namespace Board\Test\Auth;

use Board\Model\Employee;

class MockA extends Employee {

	public $id = 1;
	public $access = -1;

	public function __construct() {}

}
