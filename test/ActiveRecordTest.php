<?php
	error_reporting(E_ALL);

define("ROOT", '..');
	require ROOT ."/ini.php";
	require ROOT ."/lib/activerecord.php";
	ActiveRecord::$db = new PDO($ini['db']); 

class User
{
	static $table;

	function gul_assalam()
	{
		print $this->name ." : Aya golna slam3likom <br />";
	}
} User::$table = new ActiveRecord("users", "User");
$users = User::$table->select();
foreach ($users as $u) {
	$u->gul_assalam();
	$u->name = "Changed my name";
	User::$table->insert($u);
}
$users = User::$table->select();
print_r($users);
