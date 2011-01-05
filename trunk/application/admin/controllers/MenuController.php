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
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id:$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */

/**
 * Admin Navigation Conroller of Digitalus CMS
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @category   Digitalus CMS
 * @package    Digitalus_CMS_Controllers
 * @version    $Id: MenuController.php Mon Dec 24 20:53:11 EST 2007 20:53:11 forrest lyman $
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */
class Admin_MenuController extends Digitalus_Controller_Action
{
	private $_objMenus = null;
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
    	parent::init();
    	$this->_objMenus = new Model_Menus();
    }

    /**
     * The default action
     *
     * Display the main menu admin page
     *
     * @return void
     */
    public function indexAction()
    {
    	$id = (int) $this->_request->getParam('cat',0);
    	$conditions = "parent = 0";
    	if(!is_null($id)) $conditions = "parent = {$id}";  
        $objMenus = new Model_Menus();
		$this->view->menuLevelOnes = $objMenus->fetchAll($conditions,"order ASC");
		$this->view->menuRoot = $objMenus->fetchAll("parent = 0","order ASC");
		$this->view->currentMenu = $id;
    }
    
    public function addAction()
    {
    	$val = array('parent'=>0,'admin_panel_id'=>0,'order'=>0,'active'=>0);
    	if ($this->_request->isPost()) 
    	{
    		if(!isset($_POST['admin_menu_img'])) $_POST['admin_menu_img'] = '';
			if($this->_objMenus->insertFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}        	
        	$val = $this->_request->getParams();    		
    	}

        $this->view->menuLevelOnes = $this->_objMenus->fetchAll("parent = 0","order ASC");
		$this->view->parentMenu = $this->view->loadTreeMenuSelectBox('parent',null,$val['parent'],array('class'=>'required','size'=>1,'style'=>'width:300px;'));
		$this->view->adminPanel = $this->createSelectAdminPanel('admin_panel_id',$val['admin_panel_id'],array('style' => 'width:150px;'));
        $this->view->rowMenu = $this->_objMenus->createRow($val);
    	$this->view->title_action = $this->view->getTranslation('Add');
    }
    
    public function editAction()
    {
        if ($this->_request->isPost()) 
        {
			if(!isset($_POST['admin_menu_img'])) $_POST['admin_menu_img'] = $_POST['menu_icon'];
        	if($this->_objMenus->updateFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}
        	$rowParam = $this->_objMenus->createRow($this->_request->getParams());
        }
        else 
        {
	    	$id = $this->_request->getParam('id');
	    	$rowMenu = $this->_objMenus->find($id)->current();
	    	if(!$rowMenu)
				$this->_redirect($this->_currentControllerUrl);
        }
        
		$this->view->rowMenu = $rowMenu;
		$this->view->parentMenu = $this->view->loadTreeMenuSelectBox('parent',null,$rowMenu->parent,array('class'=>'required','size'=>1,'style'=>'width:300px;'));
		$this->view->adminPanel = $this->createSelectAdminPanel('admin_panel_id',$rowMenu->admin_panel_id,array('style' => 'width:150px;'));
		$this->view->title_action = $this->view->getTranslation('Edit');
    }    
    
    public function switchStatusAction()
    {
	   	$id = (int) $this->_request->getParam('id');
	   	$status = $this->_objMenus->switchMenuStatus($id);
    	$this->_redirect($this->_currentControllerUrl);
    }    
    
    public function saveOrderAction()
    {
    	$this->_objMenus->saveOrder($_POST['order']);
    	exit();
    }    
    
    public function deleteAction()
    {
    	$id = (int) $this->_request->getParam('id');
    	// Override phuong thuc delete trong class Menus
    	$this->_objMenus->delete($id);
    	$this->_redirect($this->_currentControllerUrl);
    }
    
    public function deleteAllAction()
    {
		$varCheckBoxList = $this->_request->getPost('checkbox');    	
		if(!is_null($varCheckBoxList))
		{
			foreach ($varCheckBoxList as $varID)
			{
				$rowMenu = $this->_objMenus->find($varID)->current();
				if($rowMenu != null)
					$this->_objMenus->delete($varID);				
			}
		}
		$this->_redirect($this->_currentControllerUrl);
    }    
    
    private function createSelectAdminPanel($name,$selected = null,$attribsSelect = null)
    {
    	$objAdminPanels = new Model_AdminPanels();
    	$rsAdminPanels = $objAdminPanels->fetchAll('active = 1', "order ASC");
    	
    	$options = array();
		$options[0] = $this->view->t("-- NONE --"); 
		
		foreach ($rsAdminPanels as $rowAdminPanel)
			$options[$rowAdminPanel->admin_panel_id] = $rowAdminPanel->title; 
			
//		$view = new Zend_View();
		return $this->view->formSelect($name, $selected, $attribsSelect, $options); 		
		
    	
    }


}