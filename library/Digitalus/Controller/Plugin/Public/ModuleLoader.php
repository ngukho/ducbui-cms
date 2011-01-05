<?php

/**
 * Digitalus CMS
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
 * @category   Digitalus CMS
 * @package   Digitalus_Core_Library
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: Interfaceh.php Tue Dec 25 20:30:05 EST 2007 20:30:05 forrest lyman $
 */

class Digitalus_Controller_Plugin_ModuleLoader extends Zend_Controller_Plugin_Abstract
{
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)  
	{  
//    	$module = $request->getModuleName();  
//  
//	    if (!isset($this->_modules[$module])) {  
//	        throw new Exception("Module does not exist!");  
//	    }  
//	  
//	    $bootstrapPath = $this->_modules[$module];  
//	  
//	    $bootstrapFile = dirname($bootstrapPath) . '/Bootstrap.php';  
//	       $class         = ucfirst($module) . '_Bootstrap';  
//	       $application   = new Zend_Application(  
//	        APPLICATION_ENV,  
//	        APPLICATION_PATH . '/modules/' . $module . '/configs/module.ini'  
//	    );    
//	  
//       if (Zend_Loader::loadFile('Bootstrap.php', dirname($bootstrapPath)) & class_exists($class)) {  
//	           $bootstrap = new $class($application);  
//	           $bootstrap->bootstrap();  
//	       }  
	       
		$front = Zend_Controller_Front::getInstance();
	       
 		// Get current module name
 		$module = $request->getModuleName();    	
    	
 		// Array module
        $modules = $front->getParam('cmsModules');
        $modulePath = $front->getParam("pathToModules");
        
        if (isset($modules[$module])) {
        	$currentModulePath = $modulePath . '/' . $modules[$module];
            $bootstrapFile = $currentModulePath . '/Bootstrap.php';
            $class = ucfirst($modules[$module]) . '_Bootstrap';  
            
       		$application = new Zend_Application(  
        			APPLICATION_ENV,
        			$currentModulePath . '/configs/module.ini'
    		);    

       		if (Zend_Loader::loadFile('Bootstrap.php',$currentModulePath) && class_exists($class)) {  
           		$bootstrap = new $class($application);  
           		$bootstrap->bootstrap();  
       		}
        }	       
	       
	}  	

}