<?php

class Digitalus_Cache_Manager
{
	private static $_instance;
	private static $_cache;
	private $notCache = false;
	
	public static function setCacheObject($objCache)
	{
		self::$_cache = $objCache;
	}
	
	public static function getInstance()
	{
		if(self::$_instance === null)
			self::$_instance = new self;
		return self::$_instance;
	}
		
    private function __construct()
    {

    }
    
    public function saveCache($data,$key,$tags = array())
    {
    	if($this->notCache) return;
    	self::$_cache->save($data, md5($key),$tags);
    }
    
    public function loadCache($key)
    {
    	 return self::$_cache->load(md5($key));
    }

    public function doNotCache($flag = true)
    {
    	$this->notCache = $flag;
    }

    public function removeCache($key,$option = Zend_Cache::CLEANING_MODE_MATCHING_TAG)
    {
    	if(is_array($key))
	    	self::$_cache->clean($option,$key);
    	else 
    		self::$_cache->remove(md5($key));
    }
    
    public function removeCacheFormPath($path,$option = Zend_Cache::CLEANING_MODE_MATCHING_TAG)
    {
    	$path = Digitalus_Toolbox_String::stripLeading('/',$path);
    	$path = Digitalus_Toolbox_Regex::stripTrailingSlash($path);
    	$path = Digitalus_Toolbox_String::addUnderscores($path);
    	
   		self::$_cache->clean($option,array($path));
    }
    
}