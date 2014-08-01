<?php
	//Symbols Definition
	define('KIT_VERSION','0.1.0');
	define('KIT_EXPIREMENTAL',true);
	define('KIT_CORE',true);
	
	require_once("util/core.php");	//FOR load & import functions.
									//Just like Coreutils..
	
	//Import Utilities
	import('util.db');
	import('util.string');
	
	//Initialize
	$ROUTE =& load("Router");
	$URI =& load('URI');
	$ROUTE->init();