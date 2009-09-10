<?php
	require "../../lib/resourcerevision.php";
	ResourceRevision::$db = new PDO("sqlite:../resources.db");
	ResourceRevision::$root = new LocalResource("http://localhost/sam/waraq/archive/", "/home/sam/waraq/archive/");

$rr = new ResourceRevision($_GET['url']);
$rr->commit();
header("Location: ". $rr->url, TRUE, 307);
