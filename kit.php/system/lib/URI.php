<?php
	class URI{
		var $keyval = array();
		var $uri_string;
		
		var $segments = array();
		var $rsegments = array();
		
		var $permitted_uri_chars = 'a-z 0-9%.:_\-';
		function __construct(){
			
		}
		
		function fetch_uri_string(){
			//TODO:Auto-detection of Protocols
			$this->set_uri_string($this->detect_uri());
			return;		
		}
		
		function set_uri_string($str){
			$str = rminv($str,false);
			$this->uri_string = ($str == '/') ? '' : $str;
		}
		
		function detect_uri(){
			if(!isset($_SERVER['REQUEST_URI']) or !isset($_SERVER['SCRIPT_NAME']))
				return '';
			
			$uri = $_SERVER['REQUEST_URI'];
			if(strpos($uri, $_SERVER['SCRIPT_NAME']) === 0)
				$uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
			elseif(strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0)
				$uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
			
			if(strncmp($uri, '?/', 2) === 0)
				$uri = substr($uri, 2);
			
			$parts = preg_split('#\?#i', $uri, 2);
			$uri = $parts[0];
			if(isset($parts[1])){
				$_SERVER['QUERY_STRING'] = $parts[1];
				parse_str($_SERVER['QUERY_STRING'], $_GET);
			}else{
				$_SERVER['QUERY_STRING'] = '';
				$_GET = array();
			}
			
			if($uri == '/' || empty($uri))
				return '/';
			
			$uri = parse_url($uri, PHP_URL_PATH);
			
			return str_replace(array('//', '../'), '/', trim($uri,'/'));
		}
/*
		function parse_cli_args(){
			$args = array_slice($_SERVER['argv'], 1);
			return $args ? '/'.implode('/', $args) : '';
		}
*/		
		function filter_uri($str){
			if(!preg_match(
					"|^[".str_replace(array('\\-','\-'),
									  '-',
									  preg_quote($this->permitted_uri_chars,
								 				 '-'))."]+$|i",
					$str))
				exit("The URI you submitted has disallowed characters.");
			$bad  = array('$',    '(',    ')',    '%28',  '%29');
			$good = array('&#36;','&#40;','&#41;','&#40;','&#41;');
			return str_replace($bad, $good, $str);
		}
		
		function remove_url_suffix(){
			//TODO:Finish this function.
		}
		
		function explode_segments(){
			foreach(explode("/",
					preg_replace("|/*(.+?)/*$|", 
							 "\\1", 
							 $this->uri_string))
				as $val){
				
				$val = trim($this->filter_uri($val));
				if($val != '')
					$this->segments[] = $val;
			}
		}
		
		function reindex_segments(){
			array_unshift($this->segments, NULL);
			array_unshift($this->rsegments, NULL);
			unset($this->segments[0]);
			unset($this->rsegments[0]);
		}
		
		function segment($n, $no_result = false){
			return (!isset($this->segments[$n])) ?
					 $no_result : $this->segments[$n];
		}
		
		function rsegment($n, $no_result = false){
			return (!isset($this->rsegments[$n])) ?
			$no_result : $this->rsegments[$n];
		}
		
		function uri_to_assoc($n = 3, $default = array()){
			return $this->_uri_to_assoc($n, $default, 'segment');
		}
		
		function ruri_to_assoc($n = 3, $default = array()){
			return $this->_uri_to_assoc($n, $default, 'rsegment');
		}
		
		function _uri_to_assoc($n = 3, $default = array(), $which = 'segment'){
			if($which == 'segment'){
				$total_segments = 'total_segments';
				$segment_array = 'segment_array';
			}else{
				$total_segments = 'total_rsegments';
				$segment_array = 'rsegment_array';
			}
			
			if(!is_numeric($n)){
				return $default;
			}
			
			if(isset($this->keyval[$n]))
				return $this->keyval[$n];
			
			if($this->$total_segments() < $n){
				if(count($default) == 0)
					return array();
				
				$retval = array();
				foreach($default as $val){
					$retval[$val] = false;
				}
				return $retval;
			}
			$segments = array_slice($this->$segments_array(), ($n - 1));
			
			$i = 0;
			$lastval = '';
			$retval = array();
			foreach($segments as $seg){
				if($i % 2)
					$retval[$lastval] = $seg;
				else{
					$retval[$seg] = false;
					$lastval = $seg;
				}
				
				$i ++;
			}
			if(conut($default) > 0){
				foreach($default as $val){
					if(! array_key_exists($val, $retval))
						$retval[$val] = false;
				}
			}
			
			$this->keyval[$n] = $retval;
			return $retval;
		}
		
		function assoc_to_uri($array){
			$temp = array();
			foreach((array)$array as $key => $val){
				$temp[] = $key;
				$temp[] = $val;
			}
			return implode('/',$temp);
		}
		
		function slash_segment($n, $where = 'trailing'){
			return $this->_slash_segment($n, $where, 'segment');
		}
		
		function slash_rsegment($n, $where = 'trailing'){
			return $this->_slash_segment($n, $where, 'rsegment');
		}
		
		function _slash_segment($n, $where = 'trailing', $which = 'segment'){
			$leading = '/';
			$trailing = '/';
			if(where == 'trailing')
				$leading = '';
			if(where == 'leading')
				$trailing = '';
			
			return $leading . $this->$which($n) . $trailing;
		}
		
		function segment_array(){
			return $this->segments;
		}
		
		function total_segments(){
			return count($this->segments);
		}
		
		function rsegment_array(){
			return $this->rsegments;
		}
		
		function total_rsegments(){
			return count($this->rsegments);
		}
		
		function uri_string(){
			return $this->uri_string;
		}
		
		function ruri_string(){
			return '/'.implode('/', $this->rsegment_array());
		}
	}