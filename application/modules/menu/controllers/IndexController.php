<?php
class Mod_Menu_IndexController extends ZendX_Controller_Action
{


    public function init()
    {
    	parent::init();
//        $this->view->breadcrumbs = array(
//           $this->view->getTranslation('Modules') => $this->getFrontController()->getBaseUrl() . '/admin/module',
//           $this->view->getTranslation('Contact') => $this->getFrontController()->getBaseUrl() . '/mod_contact'
//        );
//        $this->view->toolbarLinks[$this->view->getTranslation('Add to my bookmarks')] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark'
//            . '/url/mod_contact'
//            . '/label/' . $this->view->getTranslation('Module') . ':' . $this->view->getTranslation('Contact');
    }

    public function indexAction()
    {
//    	Zend_Loader::loadClass();
//    	Zend_Layout_Controller_Plugin_Layout

		// Load helper trong cac module
		$this->loadHelper();

//		$this->loadModel('MyMenu','admin');

		// Load model trong bat ky module nao
		$this->loadModel('MyMenu');
//		
//		$this->loadModel('MyMenu','contact');
//		
//		$this->loadModel('MyMenu','public');
//		exit();

    	$m = new MyMenu();
    	
    	$data = $m->log(5);
    	
    	echo "<pre>";
    	print_r($this->view->menuHelper("Bui Van Tien Duc"));
    	echo "<br>";
    	
    	echo "<pre>";
    	print_r($data);
    	echo "<br>";
    	print_r($this->view->currentActionUrl);
    	echo "</pre>";
    	exit();
    	
    }

}