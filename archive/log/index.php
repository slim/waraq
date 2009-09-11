<?php
	define(ROOT, "../../");
	require ROOT ."ini.php";
	require "../../lib/resourcerevision.php";
	ResourceRevision::$db = new PDO("sqlite:../resources.db");
	ResourceRevision::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	ResourceRevision::$root = $root->get("/archive/");

$revisions = ResourceRevision::select("group by md5 order by date desc limit 100");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="../../css/barred.css" />
</head>
<body>
<table>
<thead><tr><th>revision</th><th>date</th><th>origin</th></tr></thead>
<tbody>
<?php foreach ($revisions as $r) {
		$url = ResourceRevision::$root->url."/".$r->mimetype."/".$r->charset."/".$r->hash;
		print "<tr><td><a href='$url'>".$r->id."</a></td><td>".$r->date."</td><td><a href='".$r->origin."'>go</a></td></tr>";
	} ?>
</tbody>
</table>
</body>
</html>
