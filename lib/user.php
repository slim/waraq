<?php

  require_once "persistance.php";
  
  
class User implements sql, persistance
{
	static $db;
	public $id;
	public $password;
    public $name;
    public $email;
    
    function __construct($id=NULL)
    {
    	if ($id) {
  			$this->id = $id;
  		} else {
  			$this->id = uniqid();
  		}
  		
  	  $this->password = NULL;
      $this->name = NULL;
      $this->email = NULL;
    }
    
    static function set_db($db, $user = NULL, $password = NULL)
    {
    		if ($db instanceof PDO) {
    			self::$db =& $db;
    		} else {
    			if (empty($user)) {
    				self::$db =& new PDO($db);
    			} else {
    				self::$db =& new PDO($db, $user, $password);
    			}
    		}
    		self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    		return self::$db;
    }
    
    public static function sql_select($options = NULL)
  	{
  		$query = "select * from users $options";
  		return $query;
  	}

  	public function toSQLinsert()
  	{
  	  $id = $this->id;
  	  $password = $this->password;
      $name = $this->name;
      $email = $this->email;
      
      return "insert into users (id, password, name, email) values ('$id', '$password', '$name', '$email')";
  	}
  	
  	public static function select($options = NULL)
	{
	   $query = self::sql_select($options);
	   $result = self::$db->query($query);
	   $users = array();
	   	foreach ($result as $entry) {
	   		$user = new User($entry['id']);
	   		$user->password = $entry['password'];
       		$user->name = $entry['name'];
       		$user->email = $entry['email'];

	   		$users [] = $user;   
	    }        
	    return $users;
    }
    
    static function load($id)
  	{
  		list($user) = self::select("where id='$id'");
  		return $user;
  	}
  	
    public function insert()		
    {
      $query = $this->toSQLinsert();
      self::$db->exec($query);
      return $this;		   
    }

    public function save()		
    {
		return $this->insert();
	}
        
	function isAuthentic()
	{
		$id = $this->id;
		$password = $this->password;
		$query = "select * from users where id='$id' and password='$password'";
      	$result = self::$db->query($query);
		if (!$result->fetch()) {
			return false;
		}
		else {
			return true;
		}
	}
     	
}
