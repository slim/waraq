<?php
	$db_name = $_POST['dbn']; unset($_POST['dbn']);
	$db_host = $_POST['dbh']; unset($_POST['dbh']);

	require '../../lib/dbentry.php';

	if (!@mysql_connect($db_host, $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) || !@mysql_select_db($db_name)) {
    	Header("WWW-Authenticate: Basic realm=\"$db_name@$db_host\"");
    	Header("HTTP/1.0 401 Unauthorized");
		fatal_error("<b>CONNECTION ERROR</b> check your server permissions"); 
	}

	$entries = DBEntry::extract($_POST);
	$q = reset($entries)->toSQLcreate();
	mysql_query($q) or fatal_error('<b>SQL ERROR</b> ' . mysql_error()); 

	foreach ($entries as $e) {
		$q = $e->toSQLinsert();
		mysql_query($q) or fatal_error('<b>SQL ERROR</b> ' . mysql_error()); 
	}
	success("Data Entry Added <button onclick='location.href=\"". $_SERVER['HTTP_REFERER'] ."\"'>Continue</button>"); 

function fatal_error($message)
{
	die("<div style='background-color: yellow; border: 2px solid red; padding: 10px; margin: 10px;'>$message</div>");
}
function success($message)
{
	die("<div style='background-color: green; color: white; padding: 10px; margin: 10px;'>$message</div>");
}
