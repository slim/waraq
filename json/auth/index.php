<?php
	session_start();

define(ROOT, '../..');
	require ROOT ."/ini.php";
	require ROOT ."/lib/user.php";

User::set_db($ini['db'], $ini['db.user'], $ini['db.password']);

$user = new User($_GET['login']);
$user->password = $_GET['password'];
if ($user->isAuthentic()) {
	$user = User::load($user->id);
	$_SESSION['user.id'] = $user->id;
	echo json_encode($user);
}
else {
	$err = array('isError' => true, 'message' => 'Check your credentials');
	echo json_encode($err);
}
