<?php
require 'lib/localresource.php';

$url  = "http://". $_SERVER['SERVER_NAME'] ."/". $_SERVER['REQUEST_URI'];
$file = $_SERVER['SCRIPT_FILENAME'];
$here = new LocalResource($url, $file);
$root = $here->base()->get(ROOT);

$ini['db'] = "sqlite:". $root->file ."/test/waraq.db";
$ini['db.user'] = "";
$ini['db.password'] = "";
