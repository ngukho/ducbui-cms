<?php


class Virtuemart_Plugin extends Zend_Controller_Plugin_Abstract
{

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $definePath = APPLICATION_PATH . '/modules/virtuemart/defines/';
            
		$d = dir($definePath) or die("Wrong module path: $definePath");
		while (false !== ($entry = $d->read())) {
			if($entry != '.' && $entry != '..' && !is_dir($definePath.$entry)) 
				require_once($definePath . $entry);
		}
		$d->close();     

		// Tu dong autoload toan bo cac tai nguyen cua module
//        $autoLoader =  new Zend_Loader_Autoloader_Resource(array(
//            'basePath'      => APPLICATION_PATH . '/modules/virtuemart',
//            'namespace'     => '',
//            'resourceTypes' => array(
////                'form' => array(
////                    'path'      => 'admin/forms/',
////                    'namespace' => 'Admin_Form_',
////                ),
//                'model' => array(
//                    'path'      => 'models/',
//                    'namespace' => DEFAULT_VM_NAMESPACE
//                ),
//            ),
//        ));
		
			
    }
    
	public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
    	

    }
    
}