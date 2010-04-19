<?php

	interface persistance
	{
		public static function select($options = NULL);
		public function insert();
	}

	interface sql
	{
		public function toSQLinsert();
		public static function sql_select($options = NULL);
	}
