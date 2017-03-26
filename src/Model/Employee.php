<?php

namespace Board\Model;

use PDO;
use Board\Service\Connection;

class Employee {

	protected $db;

	public $id;
	public $name;
	public $email;
	public $pass;
	public $access;
	public $created;
	public $updated;

	public function __construct(Connection $db) {
		$this->db = $db;
	}

	/**
	 * Imports array values
	 * @param array $e employee
	 * @return void
	 */
	public function from_array(array $e) {
		$this->id      = isset($e["id"])      ? intval($e["id"])      : $this->id;
		$this->name    = isset($e["name"])    ? strval($e["name"])    : $this->name;
		$this->email   = isset($e["email"])   ? strval($e["email"])   : $this->email;
		$this->pass    = isset($e["pass"])    ? strval($e["pass"])    : $this->pass;
		$this->access  = isset($e["access"])  ? intval($e["access"])  : $this->access;
		$this->created = isset($e["created"]) ? intval($e["created"]) : $this->created;
		$this->updated = isset($e["updated"]) ? intval($e["updated"]) : $this->updated;
	}

	/**
	 * Exports employee values
	 * @return array
	 */
	public function to_array() {
		return [
			"id"      => intval($this->id),
			"name"    => strval($this->name),
			"email"   => strval($this->email),
			"pass"    => strval($this->pass),
			"access"  => intval($this->access),
			"created" => intval($this->created),
			"updated" => intval($this->updated),
		];
	}

	/**
	 * Loads data of employee from database
	 * @param int $id employee identificator
	 * @return void
	 */
	public function load(int $id) : void {
		$query = "SELECT *,
			unix_timestamp(created) AS created,
			unix_timestamp(updated) AS updated
			FROM employee WHERE id = ?";

		$statm = $this->db->prepare($query);
		$statm->setFetchMode(PDO::FETCH_ASSOC);
		$statm->execute([ $id ]);
		$res = $statm->fetch();
		$this->from_array($res);
	}

	/**
	 * Saves data of employee to database
	 * @return void
	 */
	public function save() : void {
		$insert = "INSERT INTO employee SET %s";
		$update = "UPDATE employee SET %s WHERE id = :id";
		$values = "id=:id,name=:name,email=:email,pass=:pass,access=:access";

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
	 * Deletes data of employee from database
	 * @return void
	 */
	public function del() : void {
		$query = "DELETE FROM employee WHERE id = ?";
		$statm = $this->db->prepare($query);
		$statm->execute([ $this->id ]);
	}

}
