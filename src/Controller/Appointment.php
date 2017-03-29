<?php

namespace Board\Controller;

use DateTime;
use Throwable;
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

	/** /appointment/popup/day-{day}/type-{type}/{id} */
	public function popup(string $day, string $type, int $id) {
		if ($type === "simple") {
			$cls = Simple::class;
		}

		if ($type === "recurrent") {
			$cls = Recurrent::class;
		}

		try {
			$appointment = new $cls($this->connection);
			$appointment->load($id);
		} catch (Throwable $e) {
			throw new PageNotFound($e);
		}

		$owners = Employee::all($this->connection);

		return ($this->view)("appointment.popup",
			compact("day", "type", "appointment", "owners"));
	}

	/** /appointment/popup/day-{day}/type-{type}/{id} */
	public function popup_submit(string $day, string $type, int $id) {
		if ($type === "simple") {
			$cls = Simple::class;
		}

		if ($type === "recurrent") {
			$cls = Recurrent::class;
		}

		if ($this->request["action"] === "update") {
			return $this->update_submit($day, $cls, $id);
		}

		if ($this->request["action"] === "delete") {
			return $this->delete_submit($day, $cls, $id);
		}
	}

	public function update_submit(string $day, string $cls, int $id) {
		if (!$this->validate_token() || !$this->validate_time()) {
			return ($this->redirect)("/");
		}

		$appointment = new $cls($this->connection);
		$appointment->load($id);
		$data = iterator_to_array($this->request);

		if (get_class($appointment) === Simple::class || (isset($this->request["all"]) &&
				filter_var($this->request["all"], FILTER_VALIDATE_BOOLEAN))) {

			$appointment->from_array($data);
			$appointment->save();
		} else {
			$appointment->except($day);
			$new_appointment = new Simple($this->connection);
			$new_appointment->day = $day;
			$new_appointment->from_array($appointment->to_array());
			$new_appointment->from_array($data);
			$new_appointment->save();
		}

		$room = $appointment->room_id;
		$ym = DateTime::createFromFormat("Y-m-d", $day)->format("Y-m");

		($this->message)("appointment.updated");
		return ($this->redirect)("/room-$room/$ym");
	}

	public function delete_submit(string $day, string $cls, int $id) {
		$appointment = new $cls($this->connection);
		$appointment->load($id);
		$data = iterator_to_array($this->request);

		if (get_class($appointment) === Simple::class || (isset($this->request["all"]) &&
				filter_var($this->request["all"], FILTER_VALIDATE_BOOLEAN))) {

			$appointment->del();
		} else {
			$appointment->except($day);
		}

		$room = $appointment->room_id;
		$ym = DateTime::createFromFormat("Y-m-d", $day)->format("Y-m");

		($this->message)("appointment.deleted");
		return ($this->redirect)("/room-$room/$ym");
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
