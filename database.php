<?php
class Database 
{
	//private static $servername 	= "MURANO";
	private static $servername 	= "192.168.1.138";
	private static $username 		= "root";
	private static $password 		= "";
	private static $databaseName	= "hudsongc_etrade";
	
	private static $conn  = null;
	
	public function __construct() {
		exit('Init function is not allowed');
	}
	
	public static function connect()
	{
	   // One connection through whole application
       if ( null == self::$conn )
       {      
	        self::$conn = new mysqli(self::$servername, self::$username, self::$password, self::$databaseName);
	        self::$conn->set_charset("utf8");
	        if (self::$conn->connect_error) {
			    die("Connection failed: " . self::$conn->connect_error);
			} 
       } 
       return self::$conn;
	}
	
	public static function disconnect()
	{
		self::$conn->close();
	}
}
?>