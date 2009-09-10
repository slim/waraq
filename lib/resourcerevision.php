<?php
	require "localresource.php";

class ResourceRevision extends LocalResource
{
	static $root, $db;
	static $endOfHTML = '';
	static $startOfHEAD = '';
	static $endOfHEAD = '';

	public $date;
	public $origin;
	public $hash;
	public $id;

	function __construct($origin, $id = NULL)
	{
		$this->id = $id;
		$this->date = date("c");
		$this->origin = $origin;
	}

	function content($content)
	{
		$this->hash = md5($content);
		if ($this->id) {
			$name = $this->id .'-'. $this->hash;
		}
		else {
			$this->id = $this->hash;
			$name = $this->hash;
		}
		$this->file = self::$root->get($name)->file;
		$this->url = self::$root->get($name)->url;
	}

	static function sql_select($wildcards = NULL)
	{
		return "select * from revisions $wildcards";
	}

	static function select($wildcards = NULL)
	{
		$q = self::sql_select($wildcards);
		$results = self::$db->query($q);
		$resources = array();
		foreach ($results as $u) {
			$origin = $u['origin'];
			$id = $u['id'];
			$p = new ResourceRevision($origin, $id);
			$p->hash = $u['md5'];
			$p->date = $u['date'];
			array_push($resources , $p);
		}

		return $resources;
		
	}

	function insert()
	{
		$id = "'". $this->id ."'";
		$md5 = "'". $this->hash ."'";
		$origin = "'". $this->origin ."'";
		$date = "'". $this->date ."'";
		self::$db->query("insert into revisions (id, md5, origin, date) values ($id, $md5, $origin, $date)");
	}

	function pull_content()
	{
		$ch = curl_init();
		$timeout = 15; // set to zero for no timeout
		curl_setopt ($ch, CURLOPT_URL, $this->origin);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		$content = curl_exec($ch);

		return $content;
	}

	static function process_content($content)
	{
		if (eregi("<head[^>]*>", $content)) {
			$content = eregi_replace("<head[^>]*>", "\\0". self::$startOfHEAD , $content);
		} else {
			$content = eregi_replace("^", "<head>". self::$startOfHEAD , $content);
		}
		if (eregi("</head>", $content)) {
			$content = eregi_replace("</head>", self::$endOfHEAD . "</head>", $content);
		} else {
			$content = eregi_replace("^", self::$endOfHEAD . "</head>", $content);
		}	
		if (eregi("</body>", $content)) {
			$content = eregi_replace("</body>", self::$endOfHTML . "</body>", $content);
		} else { 
			$content = eregi_replace("$", self::$endOfHTML . "</html>", $content);
		}

		return $content;
	}

	function commit()
	{
		$content = self::process_content($this->pull_content());
		$this->content($content);
		$revision = fopen($this->file, "w");
		if (! fwrite($revision, $content)) {
			throw new Exception("Err: Ecriture");
		}
		$this->insert();
		return $ch;
	}

	static function add_to_html($s)
	{
		self::$endOfHTML .= $s;
	}

	static function add_to_head($s)
	{
		self::$endOfHEAD .= $s;
	}

	static function add_to_head_start($s)
	{
		self::$startOfHEAD .= $s;
	}
}

function url2fileName($url)
{
	$fileName = $url;
	$forbidden = array('/',':','?','=','&','+','%');
	$fileName = ereg_replace('^http://', '', $fileName); //remove http://
	$fileName = ereg_replace('#.*$', '', $fileName); //remove fragment id
	$fileName = str_replace($forbidden, '_', $fileName);

	return $fileName;
}
