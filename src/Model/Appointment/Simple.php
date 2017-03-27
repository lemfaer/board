<?php

namespace Board\Model\Appointment;

use PDO;
use DateTime;
use Board\Service\Connection;

class Simple {

	protected $db;

	public $id;
	public $day;
	public $created;
	public $updated;
	public $owner_id;
	public $time_end;
	public $time_start;
	public $description;

	public function __construct(Connection $db) {
		$this->db = $db;
	}

	/**
	 * Imports array values
	 * @param array $s simple appointment
	 * @return void
	 */
	public function from_array(array $s) {
		$this->id          = isset($s["id"])          ? intval($s["id"])          : $this->id;
		$this->day         = isset($s["day"])         ? strval($s["day"])         : $this->day;
		$this->created     = isset($s["created"])     ? intval($s["created"])     : $this->created;
		$this->updated     = isset($s["updated"])     ? intval($s["updated"])     : $this->updated;
		$this->owner_id    = isset($s["owner_id"])    ? strval($s["owner_id"])    : $this->owner_id;
		$this->time_end    = isset($s["time_end"])    ? strval($s["time_end"])    : $this->time_end;
		$this->time_start  = isset($s["time_start"])  ? strval($s["time_start"])  : $this->time_start;
		$this->description = isset($s["description"]) ? strval($s["description"]) : $this->description;
	}

	/**
	 * Exports simple appointment values
	 * @return array
	 */
	public function to_array() {
		return [
			"id"          => intval($this->id),
			"day"         => strval($this->day),
			"created"     => intval($this->created),
			"updated"     => intval($this->updated),
			"owner_id"    => strval($this->owner_id),
			"time_end"    => strval($this->time_end),
			"time_start"  => strval($this->time_start),
			"description" => strval($this->description)
		];
	}

	/**
	 * Loads all simple appointments
	 * @param \Board\Service\Connection $db
	 * @return array
	 */
	public static function all(Connection $db) : array {
		$query = "SELECT *,
			unix_timestamp(created) AS created,
			unix_timestamp(updated) AS updated
			FROM simple_appointment";

		$statm = $db->prepare($query);
		$statm->setFetchMode(PDO::FETCH_ASSOC);
		$statm->execute();
		$res = $statm->fetchAll();

		$simples = [];
		foreach ($res as $arr) {
			$simple = new Simple($db);
			$simple->from_array($arr);
			$simples[] = $simple;
		}

		return $simples;
	}

	/**
	 * Loads all simple appointments by month
	 * @param \Board\Service\Connection $db
	 * @return array
	 */
	public static function by_month(Connection $db, string $month) : array {
		$date = DateTime::createFromFormat("Y-m", $month);

		$query = "SELECT *,
			unix_timestamp(created) AS created,
			unix_timestamp(updated) AS updated
			FROM simple_appointment
			WHERE year(day) = ? AND month(day) = ?";

		$statm = $db->prepare($query);
		$statm->setFetchMode(PDO::FETCH_ASSOC);
		$statm->execute([ $date->format("Y"), $date->format("n") ]);
		$res = $statm->fetchAll();

		$simples = [];
		foreach ($res as $arr) {
			$simple = new Simple($db);
			$simple->from_array($arr);
			$simples[] = $simple;
		}

		return $simples;
	}

	/**
	 * Loads data of simple appointment from database
	 * @param int $id simple appointment identificator
	 * @return void
	 */
	public function load(int $id) : void {
		$query = "SELECT *,
			unix_timestamp(created) AS created,
			unix_timestamp(updated) AS updated
			FROM simple_appointment WHERE id = ?";

		$statm = $this->db->prepare($query);
		$statm->setFetchMode(PDO::FETCH_ASSOC);
		$statm->execute([ $id ]);
		$res = $statm->fetch();
		$this->from_array($res);
	}

	/**
	 * Saves data of simple appointment to database
	 * @return void
	 */
	public function save() : void {
		$insert = "INSERT INTO simple_appointment SET %s";
		$update = "UPDATE simple_appointment SET %s WHERE id = :id";
		$values = "id=:id,day=:day,owner_id=:owner_id,time_end=:time_end,
			time_start=:time_start,description=:description";

		$mode = empty($this->id) ? $insert : $update;
		$query = sprintf($mode, $values);

		$statm = $this->db->prepare($query);
		$statm->setFetchMode(PDO::FETCH_ASSOC);

		$binds = $this->to_array();
		unset($binds["created"]);
		unset($binds["updated"]);

		$statm->execute($binds);
	}

	/**
	 * Deletes data of simple appointment from database
	 * @return void
	 */
	public function del() : void {
		$query = "DELETE FROM simple_appointment WHERE id = ?";
		$statm = $this->db->prepare($query);
		$statm->execute([ $this->id ]);
	}

}
