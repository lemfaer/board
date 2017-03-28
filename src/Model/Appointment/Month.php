<?php

namespace Board\Model\Appointment;

use PDO;
use DateTime;
use DateInterval;
use Board\Model\Room;
use Board\Service\Config;
use Board\Service\Connection;

class Month {

	protected $db;
	protected $conf;
	protected $room;
	protected $month;
	protected $recurs;
	protected $excepts;
	protected $simples;

	public function __construct(Config $conf, Connection $db) {
		$this->db = $db;
		$this->conf = $conf;
	}

	/**
	 * Loads all simple appointments by month
	 * @param \Board\Model\Room $room boardroom
	 * @param string $month Y-m month format
	 */
	public function load(Room $room, string $month) : void {
		$this->room = $room;
		$this->month = $month;
		$this->simples = $this->load_simples($room, $month);
		$this->recurs  = $this->load_recurs($room, $month);
		$this->excepts = $this->load_excepts($month, $this->recurs);
	}

	/**
	 * Prepares month array
	 * @return array representation
	 */
	public function prepare() : array {
		$month = [];
		$date = DateTime::createFromFormat("Y-m|", $this->month);

		foreach ($this->simples as $simple) {
			$month[$simple->day]["simple"][] = $simple;
		}

		foreach ($this->recurs as $recur) {
			$start = DateTime::createFromFormat("Y-m-d", $recur->day_start);
			$interval = $this->conf["intervals"][$recur->mode];

			while (true) {
				if ($start->format("Y-m") === $date->format("Y-m")) {
					break;
				}

				$start->add(new DateInterval("P1W"));
			}

			while (true) {
				$day = $start->format("Y-m-d");

				if ($start->format("Y-m") !== $date->format("Y-m")) {
					break;
				}

				if (array_key_exists($day, $this->excepts)) {
					continue;
				}

				$start->add($interval);
				$month[$day]["recurrent"][] = $recur;
			}
		}

		return $month;
	}

	/**
	 * Loads all simple appointments by month
	 * @param \Board\Model\Room $room boardroom
	 * @param string $month Y-m month format
	 * @return array
	 */
	protected function load_simples(Room $room, string $month) : array {
		$date = DateTime::createFromFormat("Y-m|", $month);

		$query = "SELECT *,
			unix_timestamp(created) AS created,
			unix_timestamp(updated) AS updated
			FROM simple_appointment
			WHERE room_id = ?
				AND extract(YEAR_MONTH FROM day) = ?
			ORDER BY time_start";

		$statm = $this->db->prepare($query);
		$statm->setFetchMode(PDO::FETCH_ASSOC);
		$statm->execute([ $room->id, $date->format("Ym") ]);
		$res = $statm->fetchAll();

		$simples = [];
		foreach ($res as $arr) {
			$simple = new Simple($this->db);
			$simple->from_array($arr);
			$simples[] = $simple;
		}

		return $simples;
	}

	/**
	 * Loads all recurrent appointments by month
	 * @param \Board\Model\Room $room boardroom
	 * @param string $month Y-m month format
	 * @return array
	 */
	protected function load_recurs(Room $room, string $month) : array {
		$date = DateTime::createFromFormat("Y-m|", $month);

		$query = "SELECT *,
			unix_timestamp(created) AS created,
			unix_timestamp(updated) AS updated
			FROM recurrent_appointment
			WHERE room_id = :room
				AND extract(YEAR_MONTH FROM day_start) <= :ym
				AND extract(YEAR_MONTH FROM day_end) >= :ym
			ORDER BY time_start";

		$statm = $this->db->prepare($query);
		$statm->setFetchMode(PDO::FETCH_ASSOC);
		$statm->execute([ "room" => $room->id, "ym" => $date->format("Ym") ]);
		$res = $statm->fetchAll();

		$recurs = [];
		foreach ($res as $arr) {
			$recur = new Recurrent($this->db);
			$recur->from_array($arr);
			$recurs[] = $recur;
		}

		return $recurs;
	}

	/**
	 * Loads all recurrent excepts by month
	 * @param string $month Y-m month format
	 * @param array $recurs recurrent appointments
	 * @return array
	 */
	protected function load_excepts(string $month, array $recurs) : array {
		$date = DateTime::createFromFormat("Y-m|", $month);

		$ids = array_map(function ($val) { return $val->id; }, $recurs);
		$ids = implode(",", $ids);

		$query = "SELECT *,
			unix_timestamp(created) AS created,
			unix_timestamp(updated) AS updated
			FROM recurrent_except
			WHERE extract(YEAR_MONTH FROM day) = ?
				AND for_id IN (?)";

		$statm = $this->db->prepare($query);
		$statm->setFetchMode(PDO::FETCH_ASSOC);
		$statm->execute([ $date->format("Ym"), $ids ]);
		$res = $statm->fetchAll();

		$excepts = [];
		foreach ($res as $except) {
			$day = $except["day"];
			$excepts[$day][] = $except;
		}

		return $excepts;
	}

}
