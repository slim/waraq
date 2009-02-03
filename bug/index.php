<?php
	require "../lib/bug.php";

	Bug::$db = new PDO("sqlite:bug.db");
	Bug::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$bug = new Bug($_GET['desc']);
$bug->reporter = $_GET['u'];
try {
	$bug->save();
	echo "Thank you :)";
}
catch (PDOException $e) {
	echo "ERROR: ". $e->getMessage();
}
