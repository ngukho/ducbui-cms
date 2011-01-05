<?php

class Mod_Virtuemart_IndexController extends Digitalus_Controller_Action
{

    public function init()
    {
    	parent::init();

    }

    /**
     * The default action
     *
     * Checks the permissions of the index directory
     *
     * @return void
     */
    public function indexAction()
    {
//    	$test = $this->loadModel('Virtuemart_Models_Tester');
//    	
//    	$str = $test->in_ra('bui van tien duc');
//    	
//    	echo "<pre>";
//    	print_r($str);
//    	echo "</pre>";
//    	exit();
    	
		$this->_redirect($this->_currentModuleUrl.'/category-product');  
    }

}