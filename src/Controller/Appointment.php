<?php

namespace Board\Controller;

use DateTime;
use Board\Model\Room;
use Board\Model\Employee;
use Board\Model\Appointment\Simple;
use Board\Model\Appointment\Recurrent;

class Appointment extends Controller {

	/** /appointment/create */
	public function create() {
		$rooms = Room::all($this->connection);
		$owners = Employee::all($this->connection);

		$messages = $this->message;
		$intervals = $this->conf["intervals"];

		return ($this->view)("appointment.create",
			compact("rooms", "owners", "messages", "intervals"));
	}

	/** /appointment/create */
	public function create_submit() {
		$this->request["day"] = $this->request["day_start"];

		if (!$this->validate_token() || !$this->validate_day()
				|| !$this->validate_time()) {

			return ($this->redirect)("appointment/create");
		}

		$type = filter_var($this->request["recurrent"], FILTER_VALIDATE_BOOLEAN)
			? Recurrent::class : Simple::class;

		$appointment = new $type($this->connection);
		$appointment->from_array($this->request->getArrayCopy());
		$appointment->save();

		($this->message)("appointment.created");
		return ($this->redirect)("/");
	}

	/** /appointment/popup/{type}-{id} */
	public function popup(string $type, int $id) {
		if ($type === "simple") {
			$cls = Simple::class;
		}

		if ($type === "recurrent") {
			$cls = Recurrent::class;
		}

		$appointment = new $cls($this->connection);
		$appointment->load($id);

		$owners = Employee::all($this->connection);

		return ($this->view)("appointment.popup", compact("type", "appointment", "owners"));
	}

	protected function validate_day() {
		if (!filter_var($this->request["recurrent"], FILTER_VALIDATE_BOOLEAN)) {
			return true;
		}

		if (isset($this->request["day_end"])) {
			$day_start = DateTime::createFromFormat("Y-m-d", $this->request["day_start"]);
			$day_end   = DateTime::createFromFormat("Y-m-d", $this->request["day_end"]);

			if ($day_end > $day_start) {
				return true;
			}
		}

		($this->message)("appointment.day");
		return false;
	}

	protected function validate_time() {
		$time_start = DateTime::createFromFormat("H:i", $this->request["time_start"]);
		$time_end   = DateTime::createFromFormat("H:i", $this->request["time_end"]);

		if ($time_end > $time_start) {
			return true;
		}

		($this->message)("appointment.time");
		return false;
	}

}
