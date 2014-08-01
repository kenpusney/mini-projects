<?php
	
	//Module file Importing (case-sensitive)
	function import($module){
		$module = preg_replace('/\./','/',$module);
		/*
		 * Import Order:
		 * /system/$module.php				#System Internals
		 * /application/lib/$module.php		#User Libraries
		 * /application/ext/$module.php		#User Extensions
		 * /system/lib/$module.php			#System Libraries
		 * /system/ext/$module.php			#System Extensions
		 * /								#Super Base
		 * */
		foreach(array(__SYSTEM,
					  __APP.'/lib',
					  __APP.'/ext',
					  __SYSTEM.'/lib',
					  __SYSTEM.'/ext',
					  __BASE) as $path) {
			if( file_exists($path . "/" . "$module.php") ){
				return include_once($path . "/" . "$module.php");
			}
		}
		exit("Unable import $module.");
	}
	
	//Class loader.
	function &load($class, $dir = "lib"){
		static $_classes = array();
		if(isset($_classes[$class]))
			return $_classes[$class];
		
		/*
		 * Path Checking order:
		 * /system/$dir/$class.php
		 * /application/$dir/$class.php
		 *
		 * */
		foreach(array(__SYSTEM,__APP) as $path){
			if(file_exists($path . "/$dir/"."$class.php")){
				if(class_exists($class) === false){
					require($path . "/$dir/"."$class.php");
				}
				is_loaded($class);
				$_classes[$class] = new $class();
				return $_classes[$class];
			}
		}
		exit("Failed to load $class");
	}
	
	//Is the $class loaded?
	function &is_loaded($class){
		static $_is_loaded = array();
		if($class != '')
			$_is_loaded[$class] = $class;
		return $_is_loaded;
	}