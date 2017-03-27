<?php

namespace Board\Controller;

use Throwable;
use Board\Model\Employee as Model;
use Board\Exception\PageNotFound;

class Employee extends Controller {

	/** /employee */
	public function list() {
		$access = $this->conf["access"];
		$messages = $this->message;
		$employees = Model::all($this->connection);

		return ($this->view)("employee.list",
			compact("access", "messages", "employees"));
	}

	/** /employee/create */
	public function create() {
		$access = $this->conf["access"];
		$messages = $this->message;
		$employee = new Model($this->connection);

		return ($this->view)("employee.form", 
			compact("access", "messages", "employee"));
	}

	/** /employee/create */
	public function create_submit() {
		$employee = new Model($this->connection);

		$pass = $this->validate_pass();
		$token = $this->validate_token();
		$email = $this->validate_email();
		$valid = $token && $email && $pass;

		if (!$valid) {
			return ($this->redirect)("employee/create");
		}

		$data = iterator_to_array($this->request);
		$data["access"] = $access = array_reduce(
			array_keys($data["access"]),
			function ($carry, $item) {
				return $carry | $this->conf["access"][$item];
			},
			0
		);

		$employee->from_array($data);
		$employee->save();

		($this->message)("employee.created");
		return ($this->redirect)("employee");
	}

	/** /employee/update/{id} */
	public function update(int $id) {
		$access = $this->conf["access"];
		$messages = $this->message;
		$employee = new Model($this->connection);

		try {
			$employee->load($id);
		} catch (Throwable $e) {
			throw new PageNotFound($e);
		}

		return ($this->view)("employee.form",
			compact("access", "messages", "employee"));
	}

	/** /employee/update/{id} */
	public function update_submit(int $id) {
		$employee = new Model($this->connection);

		try {
			$employee->load($id);
		} catch (Throwable $e) {
			throw new PageNotFound($e);
		}

		$pass = $this->validate_pass();
		$token = $this->validate_token();
		$email = $this->validate_email();
		$valid = $token && $email && $pass;

		if (!$valid) {
			return ($this->redirect)("employee/update/$id");
		}

		$data = iterator_to_array($this->request);
		$data["access"] = $access = array_reduce(
			array_keys($data["access"]),
			function ($carry, $item) {
				return $carry | $this->conf["access"][$item];
			},
			0
		);

		$employee->from_array($data);
		$employee->save();

		($this->message)("employee.updated");
		return ($this->redirect)("employee");
	}

	/** /employee/delete/{id} */
	public function delete(int $id) {
		$employee = new Model($this->connection);

		try {
			$employee->load($id);
		} catch (Throwable $e) {
			throw new PageNotFound($e);
		}

		return ($this->view)("employee.delete", compact("employee"));
	}

	/** /employee/delete/{id} */
	public function delete_submit(int $id) {
		$employee = new Model($this->connection);

		try {
			$employee->load($id);
		} catch (Throwable $e) {
			throw new PageNotFound($e);
		}

		$employee->del();
		($this->message)("employee.deleted");
		return ($this->redirect)("employee");
	}

	protected function validate_token() {
		return true;
	}

	protected function validate_email() {
		$email = $this->request["email"];

		if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
			($this->message)("employee.email");
			return false;
		}

		return true;
	}

	protected function validate_pass() {
		$pass = $this->request["pass"];

		if (preg_match($this->conf["pass"], $pass) !== 1) {
			($this->message)("employee.pass");
			return false;
		}

		return true;
	}

}
