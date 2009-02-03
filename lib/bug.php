<?php

class Bug
{
	static $db;
	public $description;
	
	function __construct($desc)
	{
		$this->description = $desc;
	}

	function toSQLinsert()
	{
		$desc = $this->description;
		return "insert into bug (description) values ('$desc')";
	}

	function save()
	{
		self::$db->query($this->toSQLinsert());
	}
}
