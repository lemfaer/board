<?php

namespace Board\Model\Appointment;

use PDO;
use Board\Service\Connection;

class Recurrent {

	protected $db;

	public $id;
	public $mode;
	public $created;
	public $updated;
	public $day_end;
	public $time_end;
	public $owner_id;
	public $day_start;
	public $time_start;
	public $description;

	protected $excepts;

	public function __construct(Connection $db) {
		$this->db = $db;
	}

	/**
	 * Imports array values
	 * @param array $r recurrent appointment
	 * @return void
	 */
	public function from_array(array $r) {
		$this->id          = isset($r["id"])          ? intval($r["id"])          : $this->id;
		$this->mode        = isset($r["mode"])        ? strval($r["mode"])        : $this->mode;
		$this->created     = isset($r["created"])     ? intval($r["created"])     : $this->created;
		$this->updated     = isset($r["updated"])     ? intval($r["updated"])     : $this->updated;
		$this->day_end     = isset($r["day_end"])     ? strval($r["day_end"])     : $this->day_end;
		$this->time_end    = isset($r["time_end"])    ? strval($r["time_end"])    : $this->time_end;
		$this->owner_id    = isset($r["owner_id"])    ? strval($r["owner_id"])    : $this->owner_id;
		$this->day_start   = isset($r["day_start"])   ? strval($r["day_start"])   : $this->day_start;
		$this->time_start  = isset($r["time_start"])  ? strval($r["time_start"])  : $this->time_start;
		$this->description = isset($r["description"]) ? strval($r["description"]) : $this->description;
	}

	/**
	 * Exports recurrent appointment values
	 * @return array
	 */
	public function to_array() {
		return [
			"id"          => intval($this->id),
			"mode"        => strval($this->mode),
			"created"     => intval($this->created),
			"updated"     => intval($this->updated),
			"day_end"     => strval($this->day_end),
			"time_end"    => strval($this->time_end),
			"owner_id"    => strval($this->owner_id),
			"day_start"   => strval($this->day_start),
			"time_start"  => strval($this->time_start),
			"description" => strval($this->description)
		];
	}

	/**
	 * Loads all recurrent appointments
	 * @param \Board\Service\Connection $db
	 * @return array
	 */
	public static function all(Connection $db) : array {
		$query = "SELECT *,
			unix_timestamp(created) AS created,
			unix_timestamp(updated) AS updated
			FROM recurrent_appointment";

		$statm = $db->prepare($query);
		$statm->setFetchMode(PDO::FETCH_ASSOC);
		$statm->execute();
		$res = $statm->fetchAll();

		$recurs = [];
		foreach ($res as $arr) {
			$recur = new Recurrent($db);
			$recur->from_array($arr);
			$recurs[] = $recur;
		}

		return $recurs;
	}

	/**
	 * Loads data of recurrent appointment from database
	 * @param int $id recurrent appointment identificator
	 * @return void
	 */
	public function load(int $id) : void {
		$query = "SELECT *,
			unix_timestamp(created) AS created,
			unix_timestamp(updated) AS updated
			FROM recurrent_appointment WHERE id = ?";

		$statm = $this->db->prepare($query);
		$statm->setFetchMode(PDO::FETCH_ASSOC);
		$statm->execute([ $id ]);
		$res = $statm->fetch();
		$this->from_array($res);
	}

	/**
	 * Saves data of recurrent appointment to database
	 * @return void
	 */
	public function save() : void {
		$insert = "INSERT INTO recurrent_appointment SET %s";
		$update = "UPDATE recurrent_appointment SET %s WHERE id = :id";
		$values = "id=:id,mode=:mode,day_end=:day_end,time_end=:time_end,owner_id=:owner_id,
			day_start=:day_start,time_start=:time_start,description=:description";

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
	 * Deletes data of recurrent appointment from database
	 * @return void
	 */
	public function del() : void {
		$query = "DELETE FROM recurrent_appointment WHERE id = ?";
		$statm = $this->db->prepare($query);
		$statm->execute([ $this->id ]);
	}

}
