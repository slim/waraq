<?php
	$db_name = $_GET['dbn']; unset($_GET['dbn']);
	$db_host = $_GET['dbh']; unset($_GET['dbh']);
	$db_user = $_SERVER['PHP_AUTH_USER'];
	$db_password = $_SERVER['PHP_AUTH_PW'];

	require '../../lib/dbentry.php';

	if (!@mysql_connect($db_host, $db_user, $db_password) || !@mysql_select_db($db_name)) {
    	Header("WWW-Authenticate: Basic realm=\"$db_name@$db_host\"");
    	Header("HTTP/1.0 401 Unauthorized");
		fatal_error("<b>CONNECTION ERROR</b> check your server permissions"); 
	}

	$entries = DBEntry::extract($_GET);
	foreach ($entries as $e) {
		$q = $e->toSQLinsert();
		mysql_query($q) or fatal_error('<b>SQL ERROR</b> ' . mysql_error()); 
		echo "<code>$q</code>";
	}

function fatal_error($message)
{
	die("<div style='background-color: yellow; border: 2px solid red; padding: 10px; margin: 10px;'>$message</div>");
}
