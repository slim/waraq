<?php
	define(ROOT, "../../");
	require ROOT ."ini.php";
	require "../../lib/resourcerevision.php";
	ResourceRevision::$db = new PDO("sqlite:../resources.db");
	ResourceRevision::$root = $root->get("/archive/");

$rr = new ResourceRevision($_GET['url']);
$base_url = dirname($_GET['url'] .'x');
ResourceRevision::add_to_head_start("<base href='$base_url' />");
$rr->commit();

header("Location: ". $rr->url, TRUE, 307);
