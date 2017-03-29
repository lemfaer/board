<?php

namespace Board\Controller;

use Throwable;
use Board\Model\Employee;
use Board\Exception\AccessDenied;

class Auth extends Controller {

	/** /login */
	public function login() {
		return ($this->view)("auth.login", [ "messages" => $this->message ]);
	}

	public function login_submit() {
		$email = $this->request["email"];
		$pass = $this->request["pass"];

		$employees = Employee::by_field($this->connection, "email", $email);

		if (!empty($employees)) {
			$employee = array_shift($employees);

			if (password_verify($pass, $employee->pass)) {
				$this->auth->login($employee);
				($this->redirect)("/");
			}
		}

		($this->message)("auth.fail");
		throw new AccessDenied();
	}

	/** /logout */
	public function logout() {
		$this->auth->logout();
		($this->redirect)("/");
	}

}
