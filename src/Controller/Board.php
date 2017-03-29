<?php

namespace Board\Controller;

use DateTime;
use Throwable;
use Board\Model\Room;
use Board\Model\Appointment\Month;
use Board\Exception\PageNotFound;

class Board extends Controller {

	/** /room-{room}/{Y-m} */
	public function main(int $room_id = null, string $ym = null) {
		$data = [];
		$data["time"] = $this->conf["time"];
		$data["week"] = $this->week();
		$data["month"] = $this->month($ym);
		$data["rooms"] = $this->rooms($room_id);
		$data["messages"] = $this->message;
		$data["appointment"] = $this->appointment(
			$data["rooms"]["current"],
			$data["month"]["ym"]
		);

		return ($this->view)("board.view", compact("data"));
	}

	protected function week() {
		$week = [];

		for ($i = 0; $i < 7; $i++) {
			$day = $day ?? new DateTime();
			$day = $day->modify("+1 day");

			$key = intval($day->format($this->conf["week"]["num"]));
			$val = strval($day->format($this->conf["week"]["text"]));

			$week[$key] = $val;
		}

		ksort($week);
		return $week;
	}

	protected function month(string $ym = null) {
		if ($ym === null) {
			$ym = date("Y-m");
		}

		$dt = DateTime::createFromFormat("Y-m|", $ym);
		$last = intval($dt->format("t"));
		$offset = intval($dt->format($this->conf["week"]["num"]));
		$offset = isset($header[0]) ? $offset : $offset - 1;

		return compact("ym", "offset", "last");
	}

	protected function rooms(int $room_id = null) {
		$all_rooms = Room::all($this->connection);
		$currents = $all_rooms;

		if ($room_id) {
			$currents = array_filter($currents, function ($room) use ($room_id) {
				return $room->id === $room_id;
			});
		}

		if (empty($currents)) {
			throw new PageNotFound();
		}

		$current = array_values($currents)[0];

		return [ "all" => $all_rooms, "current" => $current ];
	}

	protected function appointment(Room $room, string $ym = null) {
		$appointment = new Month($this->conf, $this->connection);
		$appointment->load($room, $ym);
		$appointment = $appointment->prepare();
		return $appointment;
	}

}
