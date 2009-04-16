<?php
	require '../ini.php';
?>
<html>
<head>
<link rel="stylesheet" href="../css/barred.css" />
<script type="text/javascript" src="../js/prototype-1.6.0.3.js"></script>
<script type="text/javascript" src="../js/gears_init.js"></script>
<script type="text/javascript" src="../js/sqlreplicator.js"></script>
</head>
<body>
<script type="text/javascript">
var test_base = '<?php echo $here->base()->url ?>';

var db = google.gears.factory.create('beta.database');
db.open('database-test');
db.execute('create table if not exists Test (Phrase text, Timestamp int)');

sqlReplicator.slave_db = db;
sqlReplicator.chunk_size = 4;
sqlReplicator.set_master(test_base+'/spike-sqlreplicator.sql.php');
sqlReplicator.set_master(test_base+'/spike-sqlreplicator.sql.2');
sqlReplicator.onComplete = function () {
  			$('console').insert('TEMINE!\n');
}

var slave = {
	show: function() {
		var rs = db.execute('select * from Test order by Timestamp desc');

		while (rs.isValidRow()) {
  			$('console').insert(rs.field(0) + '@' + rs.field(1) + '\n');
  			rs.next();
		}
		rs.close();
	},
	empty: function() {
		var rs = db.execute('delete from Test');
  		$('console').update('');
	}
}
</script>
<a class="bouton" onclick="sqlReplicator.pull()">PULL FROM MASTER</a>
<a class="bouton" onclick="slave.show()">SHOW FROM SLAVE</a>
<a class="bouton" onclick="slave.empty()">EMPTY SLAVE</a>
<pre id='console'>
</pre>
</body>
</html>
