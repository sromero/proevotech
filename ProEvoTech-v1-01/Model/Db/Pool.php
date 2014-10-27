<?php
require_once('Zend/Config.php');

/**
 * 
 */
class Db_Pool {
	/**
	 * @static 
	 * @var Zend_Config
	 */
	static protected $_config = null;

	/**
	 * @static
	 * @var array
	 */
	static protected $_adapter = array();
	
	/**
	 * 
	 * @static
	 * @param Zend_Config $config
	 * @return void
	 */
	static public function setConfig(Zend_Config &$config){
		self::$_config = $config;
	}
	
	/**
	 * @static
	 * @param string $dbName
	 * @param boolean $asArray
	 * @return Db_OpenEdge
	 * @throws Db_PoolException
	 */
	static public function &getAdapter($dbName, $asArray = false){
		if(!is_string($dbName) || !is_bool($asArray)){
			require_once('Db/PoolException.php');
			throw new Db_PoolException('Invalid parameter');
		}
		
		if(array_key_exists($dbName, self::$_adapter)){
			return self::$_adapter[$dbName];
		}

		if(self::$_config === null){
			require_once('Db/PoolException.php');
			throw new Db_PoolException('Need to set the configuration first');
		}

		if(!isset(self::$_config->$dbName)){
			require_once('Db/PoolException.php');
			throw new Db_PoolException('The definition of the database does not exist');
		}
		
		$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/database.ini', $dbName);
		
		$db = $config->resources->multidb->$dbName;

	    $config = new Zend_Config(
	        array(
	            'database' => array(
	                'adapter' => $db->adapter,
	                'params'  => array(
	                    'host'     => $db->host,
	                    'dbname'   => $db->dbname,
	                    'username' => $db->username,
	                    'password' => $db->password
	                )
	            )
	        )	
	    );

	    $db = Zend_Db::factory($config->database);
	    
		self::$_adapter[$dbName] =  $db;
		return self::$_adapter[$dbName];
	}
	
	static public function closeAdapters() {
		foreach (self::$_adapter as $k => $v) {
			$v->__destruct();
			unset(self::$_adapter[$k]);
		}
	}
}