<?php
	list($first, $count) = explode(',', $_GET['l']);
	for ($i=$first; $i < $first+$count && $i < 100; $i++) {
		echo "insert into Test values ('Slim', $i);";
	}
