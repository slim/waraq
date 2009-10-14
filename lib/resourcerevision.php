<?php
	require_once "localresource.php";

class ResourceRevision extends LocalResource
{
	static $root, $db;
	static $endOfHTML = '';
	static $startOfHEAD = '';
	static $endOfHEAD = '';

	public $date;
	public $origin;
	public $mimetype;
	public $charset;
	public $hash;
	public $id;
	public $comment;

	function __construct($origin, $id = NULL)
	{
		$this->id = $id;
		$this->date = date("c");
		$this->origin = $origin;
		$this->mimetype = "text/html";
		$this->charset = "iso-8859-1";
	}

	function content($content)
	{
		$this->hash = md5($content);
		if ($this->id) {
			$name = $this->mimetype .'/'. $this->charset .'/'. $this->id .'-'. $this->hash;
		}
		else {
			$this->id = $this->hash;
			$name = $this->mimetype .'/'. $this->charset .'/'. $this->hash;
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
			$p->mimetype = $u['mimetype'];
			$p->charset = $u['charset'];
			$p->comment = $u['comment'];
			array_push($resources , $p);
		}

		return $resources;
		
	}

	function insert()
	{
		$id = "'". $this->id ."'";
		$md5 = "'". $this->hash ."'";
		$mimetype = "'". $this->mimetype ."'";
		$charset = "'". $this->charset ."'";
		$origin = "'". $this->origin ."'";
		$date = "'". $this->date ."'";
		$comment = "'". $this->comment ."'";
		self::$db->query("insert into revisions (id, md5, mimetype, charset, origin, date, comment) values ($id, $md5, $mimetype, $charset, $origin, $date , $comment)");
	}

	function pull_content($user = NULL, $password = NULL)
	{
		$ch = curl_init();
		$timeout = 15; // set to zero for no timeout
		curl_setopt ($ch, CURLOPT_URL, $this->origin);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt ($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		if ($user) {
			curl_setopt ($ch, CURLOPT_USERPWD, $user.":".$password);
		}
		$content = curl_exec($ch);
		$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		list($mimetype, $charset) = explode(';', $content_type);
		list(,$charset) = explode('=', $this->charset);
		if ($mimetype) $this->mimetype = $mimetype;
		if ($charset) $this->charset = $charset;

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

	function commit($message = "", $user = NULL, $password = NULL)
	{
		$this->comment = $message;
		$content = self::process_content($this->pull_content($user, $password));
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
