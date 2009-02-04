<?php
	require "../../lib/bug.php";

	Bug::$db = new PDO("sqlite:../bug.db");
	Bug::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

	$bug = Bug::load($_GET['id']);
	$bug->assign($_GET['u'], $_GET['tdate']);
