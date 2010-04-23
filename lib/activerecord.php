<?php
	require "dbentry.php";

class ActiveRecord
{
	static $debug;
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
		if (self::$debug) error_log(date('c')."\n$q\n\n", 3, self::$debug);
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

	public function insert($obj, $exclude) {
		foreach ($exclude as $prop) {
			unset($obj->$prop);
		}
		$record = new DBEntry($this->table);
		$record->getProperties($obj);
		$q = $record->toSQLinsert();
		if (self::$debug) error_log(date('c')."\n$q\n\n", 3, self::$debug);
		self::$db->query($q);
		return $record;
	}
}
