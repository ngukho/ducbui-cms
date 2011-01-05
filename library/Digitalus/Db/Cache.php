<?php
/**
 * Model Caching / Row Caching
 * Based on a Blog post by Pascal Opitz on April 6 2009
 * Extended by Robert Kummer and Sascha-Oliver Prolic
 * Slightly modified and adapted by Ludwig Ruderstaller
 * http://www.contentwithstyle.co.uk/content/a-caching-pattern-for-models
 * Su dung :
 * 
  		// Cache
  		$this->_objETemps->cache->setCacheSuffix('email_template');
    	$this->_objETemps->cache->setTagsArray(array('email_template'));
    	$rsETemps = $this->_objETemps->cache->fetchAllData(NULL,$s_field,$s_type);

    	// Remove
    	$this->_objETemps->cache->clean(); // => clear all cache
    	$this->_objETemps->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('email_template'));
    	
 * 
 * 
 * 
 * 
 * 
 */

class Digitalus_Db_Cache 
{
    protected $_object = null;
    protected $_cache = null;

    protected $_cacheByDefault = NULL;
    protected $_objectMethods = null;
    protected $_frontendOptions = array();
    protected $_backendOptions = array();
    protected $_backendName = NULL;
    protected $_defaultCacheIdPrefix = '';
    protected $_lifetime = NULL;

    /**
     * the constructor
     *
     * @param mixed $object
     */
    public function __construct ($object, $frontendOptions = array(), $backendOptions = array())
    {
	    // Cache options
        $frontendOptions = array(
           'lifetime' => 1200,                      // Cache lifetime of 20 minutes
           'automatic_serialization' => true,
           'cached_entity' => $object
        );
        
        $backendOptions = array(
            'lifetime' => 3600,                     // Cache lifetime of 1 hour
            'cache_dir' => BASE_PATH . '/cache/',   // Directory where to put the cache files
        );

		$this->_backendName = 'File';
        $this->_object = $object;
        $this->_cacheByDefault = true;
        $this->_defaultCacheIdPrefix = "Digitalus_Db_Cache";        
        $this->_lifetime = 1200;
        
        $this->setFrontendOptions($frontendOptions)
             ->setBackendOptions($backendOptions);
        

    	$frontend = new Zend_Cache_Frontend_Class($this->_frontendOptions);


        try{
            $this->_cache = Zend_Cache::factory($frontend,$this->_backendName, $this->_frontendOptions, $this->_backendOptions);
//			$this->_cache = Zend_Cache::factory('Class', 'File', $this->_frontendOptions, $this->_backendOptions);            
        }catch (Zend_Cache_Exception $e){
            throw ($e);
        }
        return $this;
    }

    /**
     * the main method, calls the models from cache
     *
     * @param $method
     * @param $args
     * @return unknown
     */
    public function __call($method, $args){
        $class_methods = $this->_getObjectMethods();
        $class_methods += get_class_methods($this->_cache);
        
        if (in_array($method, $class_methods)) {
            $caller = array($this->_cache , $method);
            return call_user_func_array($caller, $args);
        }
        throw new Exception("Method " . $method . " does not exist in this class " . get_class($this->_object) . ".");
    }

    /**
     * returns object methods
     * @return array
     */
    protected function _getObjectMethods(){
        if ($this->_objectMethods === null && $this->_object !== null) {
            $class = get_class($this->_object);
            $this->_objectMethods = get_class_methods($class);
        }
        return $this->_objectMethods;
    }


    /**
     * sets the FrontendOptions for the Cache Frontend
     *
     * @param array $frontendOptions
     * @return BaseModelCache
     */
    protected function setFrontendOptions($frontendOptions){
        if (is_array($frontendOptions)){
            if (!isset($frontendOptions['cache_id_prefix'])){
                $frontendOptions['cache_id_prefix'] = $this->_defaultCacheIdPrefix;
            }
            if (!isset($frontendOptions['lifetime'])){
                $frontendOptions['lifetime'] = $this->_lifetime;
            }
            if (!isset($frontendOptions['cached_entity'])){
                $frontendOptions['cached_entity'] = $this->_object;
            }
            if(!isset($frontendOptions['cache_by_default'])){
            	$frontendOptions['cache_by_default']=$this->_cacheByDefault;
            }
            $this->_frontendOptions = $frontendOptions;
        }else{
            throw new Zend_Cache_Exception('frontendOptions must be an array.');
        }
        return $this;

    }
    
    protected function setBackendOptions($backendOptions){
    	return $this;
    }


    public function setCacheSuffix($suffix){
        if (!is_string($suffix)){
            throw new Exception('Cache Suffix must be a string');
        }
        $cachePrefix = $this->_defaultCacheIdPrefix;
        $this->_cache->setOption('cache_id_prefix', $cachePrefix . $suffix . '_');
    }

    public function clean($mode = 'all', $tags = array()){
        $this->_cache->clean($mode, $tags);
    }

    public function setTagsArray($tags = array()){
        $this->_cache->setTagsArray($tags);
    }

    public function setPriority($priority){
        $this->_cache->setPriority($priority);
    }

    public function setLifetime($lifetime = false){
        $this->_cache->setLifetime($lifetime);
    }
}
  

	