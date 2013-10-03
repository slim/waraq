<?php

class DBEntry
{
	public $table;
	public $values;

	function __construct($table)
	{
		$this->table = $table;
	}

	function set($column, $value)
	{
		$this->values[$column] = $value;
	}

	function toSQLcreate()
	{
		$table = $this->table;
		$sql = "create table if not exists $table ";
		$sql .= "(". implode(' varchar (250),', array_keys($this->values)) ." varchar(250), primary key (id))";	
		return $sql;
	}

	function toSQLinsert()
	{
		$table = $this->table;
		$sql = "REPLACE INTO $table ";
		$sql .= "(". implode(',', array_keys($this->values)) .")";
		$sql .= " VALUES ";
		$sql .= "('". implode("','", $this->values) ."')";
		return $sql;
	}

	static function extract_table($table, $table_name) {
		$entries = array();
		foreach ($table as $row) {
			$e = new DBEntry($table_name);
			foreach ($row as $column => $value) {
				$e->set($column, $value);
			}
			$entries []= $e;
		}
		return $entries;
	}

	static function extract($values)
	{
		$entries = array();
		foreach ($values as $key => $val) {
			list($table, $column) = explode(':', $key);
			if (!isset($entries[$table])) {
				$entries[$table] = new DBEntry($table);
			}
			$entries[$table]->set($column, $val);
		}
		foreach ($entries as $e) {
			if (!$e->values['id']) $e->values['id'] = uniqid();
		}
		return $entries;
	}

	function setProperties($object) {
		foreach ($this->values as $key => $value) {
			$object->$key = $value;
		}
	}

	function getProperties($object) {
		foreach ($object as $key => $value) {
			$this->values[$key] = $value;
		}
	}
}
