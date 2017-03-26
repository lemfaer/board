<?php

namespace Board\Service;

use Board\Model\Employee;
use Board\Exception\AccessDenied;

class Auth {

	protected $conf;
	protected $session;
	protected $employee;

	public function __construct(Config $conf, Connection $connection, Session $session) {
		$this->conf = $conf;
		$this->session = $session;

		if (!empty($this->session["auth"])) {
			$this->employee = new Employee($connection);
			$this->employee->load($this->session["auth"]);
		}
	}

	/**
	 * Login employee
	 * @param \Board\Model\Employee $employee
	 * @return void
	 */
	public function login(Employee $employee) : void {
		$this->session["auth"] = $employee->id;
		$this->employee = $employee;
	}

	/**
	 * Logout employee
	 * @return void
	 */
	public function logout() : void {
		unset($this->session["auth"]);
		unset($this->employee);
	}

	/**
	 * Check if employee is logged in
	 * @return bool
	 */
	public function logged() : bool {
		return !empty($this->session["auth"]) && !empty($this->employee);
	}

	/**
	 * Requires access to
	 * @param string $names access name
	 * @throws \Board\Exception\AccessDenied
	 * @return void
	 */
	public function access(string ...$names) {
		if (!empty($this->session["auth"]) && !empty($this->employee)) {
			$access = array_reduce($names, function ($carry, $item) {
				return $carry | $this->conf["access"][$item];
			}, 0);

			if (($this->employee->access & $access) === $access) {
				return;
			}
		}

		throw new AccessDenied(implode(", ", $names));
	}

}
