<?php

	interface persistance
	{
		public static function set_db($db);
		public static function select($options = NULL);
		public static function load();
		public function save();
	}

	interface sql
	{
		public function toSQLinsert();
		public static function sql_select($options = NULL);
	}	
