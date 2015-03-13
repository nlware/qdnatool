<?php
App::import('Vendor', 'RserveConnection', array('file' => 'rserve-php' . DS . 'rserve-php' . DS . 'Connection.php'));
class Rserve {

	public static $configs = null;

/**
 * __construct
 */
	public function __construct() {
		if (empty(self::$config)) {
			self::$configs = self::getConfig();
		}
	}

/**
 * getConfig
 *
 * @param string $key A key
 * @return string
 */
	public static function getConfig($key = null) {
		if (!empty($key)) {
			if (isset(self::$configs[$key]) || (self::$configs[$key] = Configure::read("Rserve.$key"))) {
				return self::$configs[$key];
			} elseif (Configure::load('rserve') && (self::$configs[$key] = Configure::read("Rserve.$key"))) {
				return self::$configs[$key];
			}
		} else {
			Configure::load('rserve');
			return Configure::read('rserve');
		}
		return null;
	}

/**
 * connect method
 *
 * @return Ambigous <boolean, Rserve_Connection>
 */
	public static function connect() {
		$connection = null;
		try {
			$connection = new Rserve_Connection(self::getConfig('host'), self::getConfig('port'), self::getConfig('debug'));
		} catch(Exception $e) {
			$connection = false;
		}
		return $connection;
	}

/**
 * execute method
 *
 * @param string $script Script to execute
 * @return bool
 */
	public static function execute($script) {
		$result = false;
		if ($connection = self::connect()) {
			try {
				$result = $connection->evalString($script);
				$connection->close();
			} catch(Exception $e) {
				$result = false;
			}
		}
		return $result;
	}

}
