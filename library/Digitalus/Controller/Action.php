<?php

abstract class Digitalus_Controller_Action extends Zend_Controller_Action
{
	protected $_errors = null;
	protected $_message = null;
	protected $_cacheManager = null;

    protected $_currentModuleUrl = null;
    protected $_currentControllerUrl = null;
    protected $_currentActionUrl = null;
    
	function init()
	{
		$this->_errors = Digitalus_View_Error::getInstance();
		$this->_message = Digitalus_View_Message::getInstance();
		$this->_cacheManager = Digitalus_Cache_Manager::getInstance();
		
		$this->view->currentModuleUrl = $this->_currentModuleUrl = $this->_request->getBaseUrl() . '/' . $this->_request->getModuleName();
		$this->view->currentControllerUrl = $this->_currentControllerUrl = $this->_currentModuleUrl . '/' . $this->_request->getControllerName();
		$this->view->currentActionUrl = $this->_currentActionUrl = $this->_currentControllerUrl . '/' . $this->_request->getActionName();

		//set Helper
//		$this->view->addHelperPath('Isc/View/Helper', 'Isc_View_Helper');
//		$this->view->addHelperPath('Isc/View/Helper/IscFrom', 'Isc_View_Helper_IscFrom');	
	}
	
	function createPaginator(&$data,$pParam = 'page',$paginator_temp='default_paging.phtml',$paginator_style='Sliding')
	{
//		$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Iterator($rows));
		$paginator = Zend_Paginator::factory($data);
		$paginator->setItemCountPerPage(Model_Parameters::getParam('per_page'));
		$paginator->setCurrentPageNumber($this->_request->getParam($pParam,0));
		$data = $paginator;
		$this->view->addScriptPath(BASE_TEMPLATES);
		// Return paging format
		return $this->view->paginationControl($paginator,$paginator_style,$paginator_temp);
	}

	/**
     * Loads specified model from module's "models" directory
     * http://www.phpmag.ru/2009/03/23/zend-framework-models-auto-loading/
     * @param   string  $class  Model classname
     * @param   string  $module Module name. By default, current module is assumed
     * @throws  Zend_Exception  In case model class not found exception would be thrown
     * @return  string Class name loaded
     */
//    public function loadModel($class, $module = null)
//    {
//    	$front = $this->getFrontController();
//        $modules = $front->getParam('cmsModules');
//        $filepath = $front->getParam("pathToModules");
//        
//    	if($module == null) 
//   			$module = $this->_request->getModuleName();
//        
//		$modelDir = $filepath . '/' . $modules[$module] . '/models';
//
////    	if($module != 'admin' && $module != 'public' && $module != 'client' && $module != null)
////    		$module = 'mod_' . $module;
////        $modelDir = $this->getFrontController()->getModuleDirectory($module) . '/models';      
////        Zend_Loader::loadClass($class, $modelDir);
//
//		require_once $modelDir . "/{$class}.php";
//
//		/*
//		The $once argument is a boolean. If TRUE, Zend_Loader::loadFile() uses the PHP function » 
//			include_once() for loading the file, otherwise the PHP function » include() is used
//		*/
//// 		Zend_Loader::loadFile("{$class}.php", $modelDir,true);
//        // if we got here - then file is included
//        return new $class;
//    }	

    public function loadModel($class)
    {
		if (class_exists($class, false) || interface_exists($class, false)) {
            return new $class;
        }
            	
		$paths = explode('_', $class);
		$classFile = $paths[count($paths) - 1];
		$file = substr($class, 0, -strlen($classFile));
		$file = APPLICATION_PATH . '/modules/' . strtolower(str_replace('_', '/', $file)) . $classFile . '.php';
		
		if (file_exists($file)) 
		{
			require_once $file;
			return new $class;
		}    	
    	return null;
    }
    
	/**
     * Loads helpers from modules : <module>/views/helpers/<filename>.php
     * 
     * @param   array  $module Module name. By default, current module is assumed
     * @throws  Zend_Exception
     */    
    public function loadHelper($module = null)
    {
    	$front = $this->getFrontController();
        $modules = $front->getParam('cmsModules');
        $filepath = $front->getParam("pathToModules");
    	
    	if($module == null) 
   			$module = array($this->_request->getModuleName());
    	$arr_modules = $module;
    	
    	foreach ($arr_modules as $module)
    	{
			$helperDir = $filepath . '/' . $modules[$module] . '/views/helpers';
			$this->view->addHelperPath($helperDir, ucfirst($modules[$module]) . '_View_Helper');    				
//    		$real_module = $module;
//			if($module != 'admin' && $module != 'public' && $module != 'client')
//    			$real_module = 'mod_' . $real_module;  
//			$path = $this->getFrontController()->getModuleDirectory($real_module);
//			$this->view->addHelperPath($path . '/Views/Helpers', ucfirst($module) . '_View_Helper');    	
    	}
    }
    
	/**
     * Loads function from : application/functions/<filename>.php
     * 
     * @param   array  $function_name Function name
     * @throws  Zend_Exception
     */    
    public function loadFunction($function_name)
    {
    	if(is_array($function_name))
    	{
    		foreach ($function_name as $name)
    			$this->loadFunction($name);
    	}
		$file = APPLICATION_PATH . '/functions/' . strtolower($function_name) . '_function.php';
		if (file_exists($file))
		{ 
			require_once $file;
			return TRUE;
		}
    	return FALSE;
    }    
	

//    public $page;
//
//    public function init()
//    {
//        $this->_helper->removeHelper('viewRenderer');
//        if (Zend_Registry::isRegistered('page')) {
//            $this->page = Zend_Registry::get('page');
//        } else {
//            $this->page = new Digitalus_Page();
//            $this->_registerPage();
//        }
//    }
//
//    protected function _registerPage()
//    {
//        Zend_Registry::set('page', $this->page);
//    }
    
    
    
}