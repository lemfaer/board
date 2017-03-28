<?php

namespace Board\Model;

use PDO;
use Board\Service\Connection;

class Room {

	protected $db;

	public $id;
	public $name;
	public $created;
	public $updated;

	public function __construct(Connection $db) {
		$this->db = $db;
	}

	/**
	 * Imports array values
	 * @param array $r room
	 * @return void
	 */
	public function from_array(array $r) {
		$this->id      = isset($r["id"])      ? intval($r["id"])      : $this->id;
		$this->name    = isset($r["name"])    ? strval($r["name"])    : $this->name;
		$this->created = isset($r["created"]) ? intval($r["created"]) : $this->created;
		$this->updated = isset($r["updated"]) ? intval($r["updated"]) : $this->updated;
	}

	/**
	 * Exports room values
	 * @return array
	 */
	public function to_array() {
		return [
			"id"      => intval($this->id),
			"name"    => strval($this->name),
			"created" => intval($this->created),
			"updated" => intval($this->updated)
		];
	}

	/**
	 * Loads all rooms
	 * @param \Board\Service\Connection $db
	 * @return array
	 */
	public static function all(Connection $db) : array {
		$query = "SELECT *,
			unix_timestamp(created) AS created,
			unix_timestamp(updated) AS updated
			FROM boardroom";

		$statm = $db->prepare($query);
		$statm->setFetchMode(PDO::FETCH_ASSOC);
		$statm->execute();
		$res = $statm->fetchAll();

		$rooms = [];
		foreach ($res as $arr) {
			$room = new Room($db);
			$room->from_array($arr);
			$rooms[] = $room;
		}

		return $rooms;
	}

	/**
	 * Loads data of room from database
	 * @param int $id room identificator
	 * @return void
	 */
	public function load(int $id = null) : void {
		$query = "SELECT *,
			unix_timestamp(created) AS created,
			unix_timestamp(updated) AS updated
			FROM boardroom";

		$query .= $id ? "WHERE id = ?" : "LIMIT 1";

		$statm = $this->db->prepare($query);
		$statm->setFetchMode(PDO::FETCH_ASSOC);
		$statm->execute($id ? [ $id ] : []);
		$res = $statm->fetch();
		$this->from_array($res);
	}

}
