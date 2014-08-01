<?php
	class Router{
		var $config;
		var $routes = array();
		var $error_routes = array();
		
		var $class = '';
		var $method = 'index';
		var $directory = '';
		var $default_controller;
		
		var $uri;
		
		function __construct(){
			$this->uri =& load("URI");	
		}
		
		function init(){
			$segments = array();
			if(is_file(__APP.'/conf/routes.php'))
				require_once __APP.'/conf/routes.php';
			$this->routes = 
					( !isset($route) or 
							!is_array($route)) ?
					array() : $route;
			unset($route);
			
			$this->default_controller = 
					( !isset($this->routes['default_controller']) or
						$this->routes['default_controller'] == '') ? 
					false : strtolower($this->routes['default_controller']);
			
			$this->uri->fetch_uri_string();
			if($this->uri->uri_string == '')
				return $this->set_default_controller();
			
			//TODO: Complete this function in URI.php
			//$this->uri->remove_url_suffix();
			$this->uri->explode_segments();
			$this->parse_routes();
			$this->uri->reindex_segments();
		}
		
		function set_default_controller(){
			if($this->default_controller === false)
				exit("Unable to determine what should be displayed."
					." A default route has not been specified in the Routing file");
			if(strpos($this->default_controller, '/') !== false){
				$x = explode('/', $this->default_controller);
				$this->set_class($x[0]);
				$this->set_method($x[1]);
				$this->set_request($x);
			}else{
				$this->set_class($this->default_controller);
				$this->set_method('index');
				$this->set_request(array($this->default_controller,'index'));
			}
			$this->uri->reindex_segments();
		}
		
		function set_request($segments = array()){
			$segments = $this->validate_request($segments);
			
			if(count($segments) == 0)
				return $this->set_default_controller();
			
			$this->set_class($segments[0]);
			
			if(isset($segments[1]))
				$this->set_method($segments[1]);
			else
				$this->set_method('index');
			
			$this->uri->rsegments = $segments;
		}
		
		function validate_request($segments){
			if(count($segments) == 0)
				return $segments;
			if(file_exists(__APP.'/controllers/'.$segments[0].'.php'))
				return $segments;
			if(is_dir(__APP.'/controllers/'.$segments[0])){
				$this->set_directory($segments[0]);
				$segments = array_slice($segments, 1);
				
				if(count($segments > 0))
					if(!file_exists(__APP.
									'/controllers/'.
									$this->fetch_directory().
									$segments[0].'.php'))
						if(!empty($this->routes['404_override'])){
							$x = explode('/', $this->routes['404_override']);
							$this->set_directory('');
							$this->set_class($x[0]);
							$this->set_method( isset($x[1])?
												$x[1] : 'index');
							return $x;
						}else
							exit("Not found ".$this->fetch_directory().$segments[0]);
				else{
					$x = explode('/',$this->default_controller);
					$this->set_class($x[0]);
					$this->set_method(  isset($x[1])?
										$x[1] : 'index');
					if(!file_exists(__APP.'/controllers/'
									.$this->fetch_directory()
									.$this->default_controller.'.php')){
						$this->directory = '';
						return array();
					}
				}
				return $segments;
			}
			if(! empty($this->routes['404_override'])){
				$x = explode('/',$this->routes['404_override']);
				$this->set_class($x[0]);
				$this->set_method(  isset($x[1])?
						$x[1] : 'index');
				return $x;
			}
			
			exit("Not found $segments[0]");
		}
		
		function parse_routes(){
			$uri = implode('/', $this->uri->segments);
			
			if(isset($this->routes[$uri]))
					return $this->set_request(explode('/', $this->routes[$uri]));
			foreach($this->routes as $key => $val){
				$key = str_replace(':any', '.+',str_replace(':num', '[0-9]+',$key));
				if(preg_match('#^'.$key.'$#',$uri)){
					if(strpos($val, "$") !== false and 
							strpos($key, '(') !== false)
						$val = preg_replace('#^'.$key."$#",$val,$uri);
					return $this->set_request(explode('/', $val));
				}
			}
			return $this->set_request($this->uri->segments);
		}
		
		function set_class($class){
			$this->class = str_replace(array('/','.'), '', $class);
		}
		
		function fetch_class(){
			return $this->class;
		}
		
		function set_method($method){
			$this->method = $method;
		}
		
		function fetch_method(){
			return $this->method;
		}
		
		function set_directory($directory){
			$this->directory = $directory;
		}
		
		function fetch_directory(){
			return $this->directory;
		}
		
		function set_overrides($routing){
			
		}
	}