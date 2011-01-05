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
		
	Xoa toan bo cache content trong controller hien tai
		- Co che cache la cache toan bo noi dung bien content trong layout
			dung dung dan (uri) lam khoa
		- Dung no khi update du lieu (insert,update)
		
	Dung cau lenh nay trong controller. Xoa toan bo cache content trong controller hien tai 		
	$this->_cacheManager->removeCacheFormPath($this->_currentControllerUrl);        				
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
    
    public $_keyTags;

	public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	// Kiem tra neu chua dang nhap thi bo qua
    	$identity = Digitalus_Auth::getIdentity();
    	if (!$identity) {
            return;
    	}
    	////////////////////////////////////////
    	
//    	$this->_cache = ZendX_Cache_Manager::getInstance();
    	$this->_cache = Digitalus_Cache_Manager::getInstance();
    	
    	// La la cac phuong thuc khac get() no se khong lay tu content tu cache ra
        if (!$request->isGet()) 
        {
            self::$doNotCache = true;
            return;
        }

        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        $path = $request->getPathInfo();

        // co loi o day , xem link de biet cach sua
        $this->_key = md5($path);
        $this->_keyTags = array($module,"{$module}_{$controller}","{$module}_{$controller}_{$action}");
        
        if (false !== ($data = $this->getCache())) 
        {
        	$response = $this->getResponse();
        	$response->setBody($data['default']);
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
        
        $response = $this->getResponse();
      	$data = $response->getBody(true);

        $this->_cache->saveCache($data,$this->_key,$this->_keyTags);
    }
    
	private function getCache()
	{
		if( ($data = $this->_cache->loadCache($this->_key)) != false) {
			return $data;
		}
		return false;
	}    
}