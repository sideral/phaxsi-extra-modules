<?php

require_once('cmsconfig.class.php');

class CMS{
	
	private $config = array();
	
	function __construct(Context $context){
		
		$load = new Loader($context);
		
		$templates = array();
		$dirs = scandir(APPD_APPLICATION);
		
		foreach($dirs as $dir){
			if($dir[0] != '.'){
				$file = APPD_APPLICATION .DS.$dir.DS.'admin'.DS.$dir.'cmsconfig.php';
				if(file_exists($file)){
					require_once($file);
					$class_name = $dir.'CMSConfig';
					$class = new $class_name();
					$config = $class->getConfig();
					foreach($config as $name => $conf){
						$this->config[$dir.'/'.$name] =	$conf;				
					}
				}
			}
		}

	}
	
	function getTemplateList(){
		
		$list = array();
		
		foreach($this->config as $path => $config){
			if(isset($config['selectable']) && $config['selectable']){
				$list[$path] = $config['name'];
			}
		}
		
		return $list;
		
	}
	
	function getConfig($path){

		return $this->config[$path];
		
	}
	
} 