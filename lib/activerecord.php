<?php
	require "dbentry.php";

class ActiveRecord
{
	static $db;
	public $table;
	public $class;
	
	function __construct($table, $class) {
		$this->table = $table;
		$this->class = $class;
	}

	public function select($options = NULL) {
		$table = $this->table;
		$q = "select * from $table $options";
		$result = self::$db->query($q)->fetchAll(PDO::FETCH_ASSOC);
		$objects = array();
		$records = DBEntry::extract_table($result, $this->table);
		foreach ($records as $r) {
			$obj = new $this->class;
			$r->setProperties($obj);
			$objects []= $obj;
		} 
		return $objects;
	}

	public function insert($obj) {
		$record = new DBEntry($this->table);
		$record->getProperties($obj);
		$q = $record->toSQLinsert();
		self::$db->query($q);
		return $record;
	}
}
