<?php

	//Convertion between slash(/) & dot(.)
	function dot2sh($path){
		return str_replace('.','/',$path);
	}
	function sh2dot($path){
		return str_replace('/','.',$path);
	}
	
	//Remove Invisible Characters
	function rminv($string,$url_encoded = true){
		$non_displayables = array();
		
		if($url_encoded){
			$non_displayables[] = '/%0[0-8bcef]/';
			$non_displayables[] = '/%1[0-9a-f]/';
		}
		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';
		do{
			$string =  preg_replace($non_displayables, '',$string,-1,$count);
		}while($count);
		return $string;
	}
	
	//Escaping html
	function html_escape($var){
		if(is_array($var))
			return array_map('html_escape', $var);
		else
			return htmlspecialchars($var, ENT_QUOTES);
	}
