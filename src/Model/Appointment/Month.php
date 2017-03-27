<?php

namespace Board\Model\Appointment;

use PDO;
use DateTime;
use DateInterval;
use Board\Service\Config;
use Board\Service\Connection;

class Month {

	protected $db;
	protected $conf;
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
	 * @param string $month Y-m month format
	 */
	public function load(string $month) : void {
		$this->month = $month;
		$this->simples = $this->load_simples($month);
		$this->recurs  = $this->load_recurs($month);
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

			$offset = $date->diff($start);
			$start->add($offset);

			// substract 1 day cause Y-m| sets day to 01.
			// @see http://php.net/manual/datetime.createfromformat.php
			$start->sub(new DateInterval("P1D"));

			$interval = $this->conf["intervals"][$recur->mode];
			while (true) {
				$start->add($interval);
				$day = $start->format("Y-m-d");

				if ($start->format("Y-m") !== $date->format("Y-m")) {
					break;
				}

				if (array_key_exists($day, $this->excepts)) {
					continue;
				}

				$month[$day]["recurrent"][] = $recur;
			}
		}

		return $month;
	}

	/**
	 * Loads all simple appointments by month
	 * @param string $month Y-m month format
	 * @return array
	 */
	protected function load_simples(string $month) : array {
		$date = DateTime::createFromFormat("Y-m|", $month);

		$query = "SELECT *,
			unix_timestamp(created) AS created,
			unix_timestamp(updated) AS updated
			FROM simple_appointment
			WHERE extract(YEAR_MONTH FROM day) = ?";

		$statm = $this->db->prepare($query);
		$statm->setFetchMode(PDO::FETCH_ASSOC);
		$statm->execute([ $date->format("Ym") ]);
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
	 * @param string $month Y-m month format
	 * @return array
	 */
	protected function load_recurs(string $month) : array {
		$date = DateTime::createFromFormat("Y-m|", $month);

		$query = "SELECT *,
			unix_timestamp(created) AS created,
			unix_timestamp(updated) AS updated
			FROM recurrent_appointment
			WHERE extract(YEAR_MONTH FROM day_start) <= :ym
				AND extract(YEAR_MONTH FROM day_end) >= :ym";

		$statm = $this->db->prepare($query);
		$statm->setFetchMode(PDO::FETCH_ASSOC);
		$statm->execute([ "ym" => $date->format("Ym") ]);
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
