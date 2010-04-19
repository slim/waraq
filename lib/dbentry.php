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

	function toSQLinsert()
	{
		$table = $this->table;
		$sql = "INSERT INTO $table ";
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
