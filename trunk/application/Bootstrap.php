<?php
/**
 * Bootstrap of Digitalus CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id:$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.8.0
 */

/**
 * Bootstrap of Digitalus CMS
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    Release: @package_version@
 * @link       http://www.digitaluscms.com
 * @since      Release 1.8.0
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Initialize the autoloader
     *
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
    	// Load thu vien Digitalus chi khi nao su dung
//		require_once BASE_PATH.'/library/Digitalus/Autoloader.php';
//		$autoloader = Zend_Loader_Autoloader::getInstance();
//		$autoloader->unshiftAutoloader(new Digitalus_Autoloader(), 'Digitalus');

		// Load Model cua Module khi su dung
		// Ex : $c = new Virtuemart_Models_Categories();
		// No se tu dong load doi tuong Virtuemart_Models_Categories vao
		require_once BASE_PATH.'/library/Digitalus/Module/Autoloader.php';
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->unshiftAutoloader(new Digitalus_Module_Autoloader());

        // Ensure front controller instance is present
        $this->bootstrap('frontController');
        // Get frontController resource
        $this->_front = $this->getResource('frontController');
        
		$this->_front->setBaseUrl(BASE_URL); // set the base url!        

        // Add autoloader empty namespace
        $autoLoader =  new Zend_Loader_Autoloader_Resource(array(
            'basePath'      => APPLICATION_PATH,
            'namespace'     => '',
            'resourceTypes' => array(
//                'form' => array(
//                    'path'      => 'admin/forms/',
//                    'namespace' => 'Admin_Form_',
//                ),
                'model' => array(
                    'path'      => 'models/',
                    'namespace' => 'Model_'
                ),
            ),
        ));
        // Return it, so that it can be stored by the bootstrap
        return $autoLoader;
    }

    /**
     * Initialize the local php configuration
     *
     * @return void
     */
    protected function _initPhpConfig()
    {
    }
    

    /**
     * Initialize the site configuration
     *
     * @return Zend_Config_Xml
     */
    protected function _initConfig()
    {
        // Retrieve configuration from file
        $config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/config.xml', APPLICATION_ENV);

        // Add config to the registry so it is available sitewide
        $registry = Zend_Registry::getInstance();
        $registry->set('config', $config);
        
        // Return it, so that it can be stored by the bootstrap
        return $config;
    }

    /**
     * Initialize the cache
     *
     * @return Zend_Cache_Core
     */
    protected function _initCache()
    {
        // Cache options
        $frontendOptions = array(
           'lifetime' => 1800,                      // Cache lifetime of 30 minutes
           'automatic_serialization' => true,
        );
        $backendOptions = array(
            'lifetime' => 3600,                     // Cache lifetime of 1 hour
            'cache_dir' => BASE_PATH . '/cache/',   // Directory where to put the cache files
        );
        
        // Get a Zend_Cache_Core object
        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
        Zend_Registry::set('cache', $cache);
        
        Digitalus_Cache_Manager::setCacheObject($cache);        
        
        // Return it, so that it can be stored by the bootstrap
        return $cache;
    }

    /**
     * Initialize the PluginLoader class file include cache
     *
     * 
     */
    protected function _initPluginLoaderCache()
    {
        $this->bootstrap('config');
        // Get config resource
        $config = $this->getResource('config');
    	
        // Getting Better Performance for Plugins : Using the PluginLoader class file include cache
		$classFileIncCache = BASE_PATH . '/cache/pluginLoaderCache.php';
		if (file_exists($classFileIncCache)) {
		    include_once $classFileIncCache;
		}
		if ($config->enablePluginLoaderCache) {
    		Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);
		}
    }   
     
    /**
     * Initialize data base
     *
     * @return Zend_Db_Adapter_...???
     */
    protected function _initDb()
    {
        $this->bootstrap('config');
        // Get config resource
        $config = $this->getResource('config');

#        $resource = $this->getPluginResource('db');
#        $db = $resource->getDbAdapter();
#        $db = $this->getResource('db');
        // Setup database
        $db = Zend_Db::factory($config->database->adapter, $config->database->toArray());
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        
        // 2 dong nay bi loi khi dung Tieng Viet
//        $db->query("SET NAMES 'utf8'");
//        $db->query("SET CHARACTER SET 'utf8'");

        Zend_Db_Table::setDefaultAdapter($db);
        
        $this->bootstrap('cache');
        // Get cache object
        $cache = $this->getResource('cache');
        Zend_Locale::setCache($cache);
        // Next, set the cache to be used with all table objects
		Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
		
        // Return it, so that it can be stored by the bootstrap
        return $db;
    }
    
	/**
	 * TODO: Use Zend_Application_Resource_Session
	 * // Neu khong dung Session luu trong db thi co the bo ham nay
	 */
	protected function _initSession()
	{
		/** 
		 * Registry session handler 
		 */
//		Zend_Session::setSaveHandler(Digitalus_SessionHandler::getInstance());
//		if (isset($_GET['PHPSESSID'])) {
//			session_id($_GET['PHPSESSID']);
//		} else if (isset($_POST['PHPSESSID'])) {
//			session_id($_POST['PHPSESSID']);
//		}
	}

    /**
     * Initialize the site settings
     *
     * @return stdObject
     */
    protected function _initSiteSettings()
    {
        $siteSettings = new Model_SiteSettings();
        Zend_Registry::set('siteSettings', $siteSettings);
        // Return it, so that it can be stored by the bootstrap
        return $siteSettings;
    }

    /**
     * Initialize the site's locale
     *
     * @return Zend_Locale
     */
    protected function _initLocale()
    {
        $this->bootstrap('config');
        // Get config resource
        $config = $this->getResource('config');

        $this->bootstrap('cache');
        // Get cache object
        $cache = $this->getResource('cache');
        Zend_Locale::setCache($cache);

        $this->bootstrap('siteSettings');
        // Get siteSettings object
        $siteSettings = $this->getResource('siteSettings');

        // Set default locale
        setlocale(LC_ALL, $config->language->defaultLocale);
        $locale = new Zend_Locale($config->language->defaultLocale);

        // Set default timezone
        $timezone = $siteSettings->get('default_timezone');
        date_default_timezone_set($timezone);
        // Return it, so that it can be stored by the bootstrap
        return $locale;
    }

    /**
     * Initialize the view
     *
     * @return Zend_View
     */
    protected function _initView()
    {
        // Initialize view
        $view = new Zend_View();

//        $this->bootstrap('siteSettings');
//        // Get settings resource
//        $settings = $this->getResource('siteSettings');
//
//        // Set doctype and charset
//        $view->doctype($settings->get('doc_type'));
//        $view->placeholder('charset')->set($settings->get('default_charset'));

        // Add the view to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);

        // Load digitalus helpers

        // base helpers
//        $view->addHelperPath('Digitalus/View/Helper', 'Digitalus_View_Helper');
//        $view->addHelperPath('Digitalus/Content/Control', 'Digitalus_Content_Control');
        
		// Get $helperDirs from cache object
        $cache = $this->getResource('cache');
        if( ($helperDirs = $cache->load('helper_dirs')) == false) {
			$helperDirs = Digitalus_Filesystem_Dir::getDirectories(BASE_PATH . '/library/Digitalus/View/Helper');
			$cache->save($helperDirs,'helper_dirs');
		}
//		$helperDirs = Digitalus_Filesystem_Dir::getDirectories(BASE_PATH . '/library/Digitalus/View/Helper');
        
        if (is_array($helperDirs)) {
            foreach ($helperDirs as $dir) {
                $view->addHelperPath(BASE_PATH . '/library/Digitalus/View/Helper/' . $dir, 'Digitalus_View_Helper_' . ucfirst($dir));
            }
        }
        $view->baseUrl = $this->_front->getBaseUrl();
        // Return it, so that it can be stored by the bootstrap
        return $view;
    }
    /**
     * Initialize the controllers
     *
     * @return void
     */
    protected function _initControllers()
    {
        $this->bootstrap('config');
        // Get config resource
        $config = $this->getResource('config');
    	
        $modules = $config->globalModules->module->toArray();
        
        // Setup core cms modules
        $this->_front->addControllerDirectory(APPLICATION_PATH . '/admin/controllers', 'admin');
        $this->_front->addControllerDirectory(APPLICATION_PATH . '/public/controllers', 'public');

        // Setup extension modules
        $this->_front->setParam('pathToModules', APPLICATION_PATH . '/modules');
        $cmsModules = null;
        if ($modules = Digitalus_Module::getModules()) {
            foreach ($modules as $module) {
                $cmsModules['mod_' . $module] = $module;
                $this->_front->addControllerDirectory(APPLICATION_PATH . '/modules/' . $module . '/controllers', 'mod_' . $module);
            }
        }
        if (is_array($cmsModules)) {
            $this->_front->setParam('cmsModules', $cmsModules);
        }
    }
    
    /**
     * This function require module define files : application/module 
     *
     * @return void
     */        
    protected function _initModuleDefines()
    {
    	$options = $this->getApplication()->getOptions(); 
    	$plugins = $options['digitalus_plugins'];
    	
 		//Create a router and request object
// 		$router = new Zend_Controller_Router_Rewrite();
 		$router = $this->_front->getRouter(); 
 		$request = new Zend_Controller_Request_Http();
 		$router->route($request);

 		// Get current module name
 		$module = $request->getModuleName();
 		$index = $module;
 		if($module != 'admin' && $module != 'client' && $module != 'public') $index = 'admin';
 		$plugins = $options['digitalus_plugins'][$index];

//		$this->_front->registerPlugin(new Digitalus_Controller_Plugin_Initializer());
//		$this->_front->registerPlugin(new Digitalus_Controller_Plugin_Auth());
		
 		foreach ($plugins as $key => $class)
 			$this->_front->registerPlugin(new $class);
 		
 		// Array module
        $modules = $this->_front->getParam('cmsModules');
//        $filepath = $this->_front->getParam("pathToModules");
        $modulePath = $this->_front->getParam("pathToModules");
        
//        if (isset($modules[$module])) {
//            $modulePluginPath = $filepath . '/' . $modules[$module] . '/' . $modules[$module] . '.php' ;
//            require_once($modulePluginPath);
//            $pluginName = ucfirst($modules[$module]) . '_Plugin';
//            $this->_front->registerPlugin(new $pluginName);
//        }

		// Theo cam tinh , cach nay cham hon cach cu 1 chut (nhung khong dang ke) 1.25s voi 1.3s trung binh
        if (isset($modules[$module])) {
        	$currentModulePath = $modulePath . '/' . $modules[$module];
            $bootstrapFile = $currentModulePath . '/Bootstrap.php';
            $class = ucfirst($modules[$module]) . '_Bootstrap';  
            
            $moduleInit = $currentModulePath . '/configs/module.ini';
            if(!file_exists($moduleInit)) $moduleInit = null;
            
       		$application = new Zend_Application(APPLICATION_ENV,$moduleInit);

       		if (Zend_Loader::loadFile('Bootstrap.php',$currentModulePath) && class_exists($class)) {  
           		$bootstrap = new $class($application);  
           		$bootstrap->bootstrap();  
       		}
        }	       
        
    }    
    
    
    // Run bootstrap module
    // http://lenss.nl/2010/02/zend-framework-bootstrapping-modules/
//    protected function _runModuleBootstrap()
//    {
// 		// Get current module name
// 		$module = $request->getModuleName();    	
//    	
// 		// Array module
//        $modules = $this->_front->getParam('cmsModules');
//        $modulePath = $this->_front->getParam("pathToModules");
//        
//        if (isset($modules[$module])) {
//        	$currentModulePath = $modulePath . '/' . $modules[$module];
//            $bootstrapFile = $currentModulePath . '/Bootstrap.php';
//            $class = ucfirst($modules[$module]) . '_Bootstrap';  
//            
//       		$application = new Zend_Application(  
//        			APPLICATION_ENV,
//        			$currentModulePath . '/configs/module.ini'
//    		);    
//
//       		if (Zend_Loader::loadFile('Bootstrap.php',$currentModulePath) && class_exists($class)) {  
//           		$bootstrap = new $class($application);  
//           		$bootstrap->bootstrap();  
//       		}
//        }	       
//    }

}
?>