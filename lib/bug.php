<?php

class Bug
{
	static $db;
	public $id;
	public $description;
	public $reporter;
	public $status;
	
	function __construct($desc)
	{
		$this->description = $desc;
	}

	function toSQLinsert()
	{
		$desc = $this->description;
		$reporter = $this->reporter;
		$date = date('c');
		return "insert into bug (description, reporter, status, date_created) values ('$desc', '$reporter', 'new', '$date')";
	}

	function save()
	{
		self::$db->query($this->toSQLinsert());
	}
}
