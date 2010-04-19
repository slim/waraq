<?php
	error_reporting(E_ALL);

define("ROOT", '..');
	require ROOT ."/ini.php";
	require ROOT ."/lib/activerecord.php";
	require ROOT ."/lib/persistance.php";
	ActiveRecord::$db = new PDO($ini['db']); 

class User implements persistance
{
	static $table;

	function gul_assalam()
	{
		print $this->name ." : Aya golna slam3likom <br />";
	}

	function insert()
	{
		return self::$table->insert($this);
	}

	static function select($options = NULL)
	{
		return self::$table->select($options);
	}

} User::$table = new ActiveRecord("users", "User");

$users = User::select();
foreach ($users as $u) {
	$u->gul_assalam();
	$u->name = "Changed my name";
	$u->insert();
}
$users = User::select();
print_r($users);
