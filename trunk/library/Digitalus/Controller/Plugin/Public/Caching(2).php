<?php

/**
 *  http://blog.astrumfutura.com/archives/381-Zend-Framework-Page-Caching-Part-2-Controller-Based-Cache-Management.html
 *  http://www.survivethedeepend.com/zendframeworkbook/en/1.0/performance.optimisation.for.zend.framework.applications
 *  http://nqdung.plus.vn/?p=278
 *  http://devzone.zend.com/article/3372
 * 	http://www.maycode.com/index.php/hotspot/32-web20/1110-zend.html
 	Our cache criteria will be as follows:

		- Cache configuration will be passed to the constructor
		- Only GET requests will be cached
		- Redirects will not be cached
		- Any given action can tell the plugin to skip caching
 */

class Digitalus_Controller_Plugin_Caching extends Zend_Controller_Plugin_Abstract
{
/**
     *  @var bool Whether or not to disable caching
     */
    public static $doNotCache = false;

    /**
     * @var Zend_Cache_Frontend
     */
    public $_cache;

    /**
     * @var string Cache key
     */
    public $_key;

    /**
     * Constructor: initialize cache
     * 
     * @param  array|Zend_Config $options 
     * @return void
     * @throws Exception
     */
    public function __construct()
    {
    	// Khoi tao cache
    	
//    	$this->_cache = Zend_Registry::get('cache');
    	
 		// Cache options
        $frontendOptions = array(
           'lifetime' => 1200,                      // Cache lifetime of 20 minutes
           'automatic_serialization' => true,
        );
        $backendOptions = array(
            'lifetime' => 3600,                     // Cache lifetime of 1 hour
            'cache_dir' => BASE_PATH . '/cache/',   // Directory where to put the cache files
        );
        // Get a Zend_Cache_Core object
        $this->_cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
//        Zend_Registry::set('cache', $cache);
        // Return it, so that it can be stored by the bootstrap
//        return $cache;    	
    	
//        if ($options instanceof Zend_Config) {
//            $options = $options->toArray();
//        }
//        if (!is_array($options)) {
//            throw new Exception('Invalid cache options; must be array or Zend_Config object');
//        }
//
//        if (array('frontend', 'backend', 'frontendOptions', 'backendOptions') != array_keys($options)) {
//            throw new Exception('Invalid cache options provided');
//        }
//
//        $options['frontendOptions']['automatic_serialization'] = true;
//
//        $this->cache = Zend_Cache::factory(
//            $options['frontend'],
//            $options['backend'],
//            $options['frontendOptions'],
//            $options['backendOptions']
//        );
    }

    /**
     * Start caching
     *
     * Determine if we have a cache hit. If so, return the response; else,
     * start caching.
     * 
     * @param  Zend_Controller_Request_Abstract $request 
     * @return void
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
    	
//    	echo "<pre>";
//    	print_r($request->getRequestUri());
//    	echo "</pre>";
//    	exit();
    	
        if (!$request->isGet()) 
        {
            self::$doNotCache = true;
            return;
        }

        $path = $request->getPathInfo();

        
        // co loi o day , xem link de biet cach sua
        $this->_key = md5($path);
        if (false !== ($response = $this->getCache())) 
        {
            $response->sendResponse();
            exit;
        }
        
    }

    /**
     * Store cache
     * 
     * @return void
     */
    public function dispatchLoopShutdown()
    {
        if (self::$doNotCache || $this->getResponse()->isRedirect() || (null === $this->_key)) 
        {
            return;
        }

        $this->_cache->save($this->getResponse(), $this->_key);
    }
    
	private function getCache()
	{
		if( ($response = $this->_cache->load($this->_key)) != false) {
			return $response;
		}
		return false;
	}    
}