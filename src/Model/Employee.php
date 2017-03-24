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

		$this->id      = intval($res["id"]);
		$this->name    = strval($res["name"]);
		$this->email   = strval($res["email"]);
		$this->pass    = strval($res["pass"]);
		$this->access  = intval($res["access"]);
		$this->created = intval($res["created"]);
		$this->updated = intval($res["updated"]);
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

		$binds = [
			"id"     => intval($this->id),
			"name"   => strval($this->name),
			"email"  => strval($this->email),
			"pass"   => strval($this->pass),
			"access" => intval($this->access)
		];

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
