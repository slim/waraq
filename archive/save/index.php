<?php
	define(ROOT, "../../");
	require ROOT ."ini.php";
	require "../../lib/resourcerevision.php";
	ResourceRevision::$db = new PDO("sqlite:../resources.db");
	ResourceRevision::$root = $root->get("/archive/");

$rr = new ResourceRevision($_GET['url']);
$rr->commit();
header("Location: ". $rr->url, TRUE, 307);
