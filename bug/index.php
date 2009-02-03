<?php
	require "../lib/bug.php";

	Bug::$db = new PDO("sqlite:bug.db");

$bug = new Bug($_GET['desc']);
$bug->save();
