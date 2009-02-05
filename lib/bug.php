<?php

class Bug
{
	static $db;
	public $id;
	public $description;
	public $reporter;
	public $status;
	public $responsible;
	public $date_created;
	public $date_closed;
	public $target_date;
	
	function __construct($desc = NULL)
	{
		$this->description = $desc;
	}

	static function toSQLselect($options)
	{
		return "select * from bug $options";
	}

	static function select($options)
	{
		$result = self::$db->query(self::toSQLselect($options));
		$bugs = array();
		foreach ($result as $row) {
			$bugs []= Bug::fromArray($row);
		}
		return $bugs;
	}

	static function fromArray($a)
	{
		$bug = new Bug($a['description']);
		$bug->id = $a['id'];
		$bug->reporter = $a['reporter'];
		$bug->status = $a['status'];
		$bug->responsible = $a['responsible'];
		$bug->date_created = $a['date_created'];
		$bug->date_closed = $a['date_closed'];
		$bug->target_date = $a['target_date'];

		return $bug;
	}

	static function load($id)
	{
		list($bug) = self::select("where id='$id'");
		return $bug;
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

	function assign($person, $date)
	{
		$id = $this->id;
		self::$db->query("update bug set responsible='$person', target_date='$date', status='assigned' where id='$id'");
	}
}
