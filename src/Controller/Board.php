<?php

namespace Board\Controller;

use DateTime;
use Board\Model\Appointment\Month;

class Board extends Controller {

	/** /{Y-m} */
	public function main(string $ym = null) {
		if ($ym === null) {
			$ym = date("Y-m");
		}

		$appointment = new Month($this->conf, $this->connection);
		$appointment->load($ym);
		$appointment = $appointment->prepare();

		$header = [];

		for ($i = 0; $i < 7; $i++) {
			$day = $day ?? new DateTime();
			$day = $day->modify("+1 day");

			$key = intval($day->format($this->conf["week"]["num"]));
			$val = strval($day->format($this->conf["week"]["text"]));

			$header[$key] = $val;
		}

		ksort($header);

		$dt = DateTime::createFromFormat("Y-m|", $ym);
		$last = intval($dt->format("t"));
		$offset = intval($dt->format($this->conf["week"]["num"]));
		$offset = isset($header[0]) ? $offset : $offset - 1;

		return ($this->view)("board.view",
			compact("appointment", "header", "offset", "last", "ym"));
	}

}
