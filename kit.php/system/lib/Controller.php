<?php
class Controller{
	private static $instance;
	public function __construct(){
		self::$instance =& $this;
		foreach(is_loaded() as $var => $class)
			$this->$var =& load($class);
		//TODO:Finish Loader class
		//$this->load =& load("Loader");
		//$this->load->init();
	}
	public static function &get_instance(){
		return self::$instance;
	}
}
