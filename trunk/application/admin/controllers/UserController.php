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

/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';

/**
 * Admin User Conroller of Digitalus CMS
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @category   Digitalus CMS
 * @package    Digitalus_CMS_Controllers
 * @version    $Id: UserController.php Tue Dec 25 19:48:48 EST 2007 19:48:48 forrest lyman $
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */
class Admin_UserController extends Digitalus_Controller_Action
{
	private $_objUser = null;
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
    	parent::init();
    	$this->_objUser = new Model_User();
    }

    /**
     * The default action
     *
     * Render the user management interface
     *
     * @return void
     */
    public function indexAction()
    {
//    	$this->loadLeftMenu();
    	$s_field = $this->_request->getParam('s_field');
    	$s_type = $this->_request->getParam('s_type');
    	
    	$objUser = new Model_User();
    	$rsUser = $objUser->fetchAllData(NULL,$s_field,$s_type);
    	
//    	$this->view->strPaging  = $this->createPaginator($rsUser);
    	$this->view->rsUser = $rsUser;
    	
    	// Headers column
    	$this->view->firstNameTX = $this->view->SortColumn($this->view->getTranslation('First Name'),'first_name',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->lastNameTX = $this->view->SortColumn($this->view->getTranslation('Last Name'),'last_name',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->emailTX = $this->view->SortColumn($this->view->getTranslation('Email'),'email',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->roleTX = $this->view->SortColumn($this->view->getTranslation('Role'),'role',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->lastLoginTX = $this->view->SortColumn($this->view->getTranslation('Last Login'),'last_login',$s_type,$this->_currentActionUrl,$s_field);
//    	$this->view->activeTX = $this->view->SortColumn('Active','active',$s_type,$this->_currentActionUrl,$s_field);
    	
    }
    
    public function addAction()
    {
		$val = array();   	
        if ($this->_request->isPost()) 
        {
        	if($this->_objUser->insertFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}        	
        	$val = $this->_request->getParams();
        }
		
		$rowUser = $this->_objUser->createRow($val);
		$this->view->rowUser = $rowUser;
		$this->view->title_action = $this->view->getTranslation("Add");
		$this->view->action = "Add";
    }    

    public function editAction()
    {
    	$active_tab = 0;
        if($this->_request->isPost()) 
        {
        	// Change password action
        	if (Digitalus_Filter_Post::has('change_password')) 
        	{
            	$user_id = Digitalus_Filter_Post::int('user_id');
            	$password = Digitalus_Filter_Post::get('password');
            	$passwordConfirm = Digitalus_Filter_Post::get('confirm_password');
            	
            	if($this->_objUser->validateExtData($_POST))
            	{
            		if($this->_objUser->updatePassword($user_id,$password,true,$passwordConfirm))
            		{
            			$this->_redirect($this->_currentControllerUrl);
        				return;
            		}
            	}
            	$active_tab = 1;
        	} 
        	// Edit account infomation action
        	elseif($this->_objUser->updateFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}
        	$rowUser = $this->_objUser->find(Digitalus_Filter_Post::int('user_id'))->current();
        }
        else 
        {
	    	$id = $this->_request->getParam('id');
	    	$rowUser = $this->_objUser->find($id)->current();
	    	if(!$rowUser)
				$this->_redirect($this->_currentControllerUrl);
        }
        $this->view->active_tab = $active_tab;
		$this->view->rowUser = $rowUser;
		$this->view->title_action = $this->view->getTranslation("Edit");
		$this->view->action = "Edit";

    }    

    public function switchStatusAction()
    {
    	$id = $this->_request->getParam('id');
    	$status = 0;
    	if($this->_objUser->find($id)->current())
    		$status = $this->_objUser->switchStatus($id,'active');
//		$this->_redirect($this->_currentControllerUrl);
		echo $status;
		exit();
    }
    
    /**
     * Delete action
     *
     * Delete a user
     *
     * @return void
     */
    public function deleteAction()
    {
		$id = $this->_request->getParam('id');
		if(!is_null($id))
			$this->_objUser->find($id)->current()->delete();			
		$this->_redirect($this->_currentControllerUrl);    	
    }
    
    public function deleteAllAction()
    {
		$varCheckBoxList = $this->_request->getPost('checkbox');    	
		if(!is_null($varCheckBoxList))
		{
			foreach ($varCheckBoxList as $varID)
				$this->_objUser->find($varID)->current()->delete();			
		}
		$this->_redirect($this->_currentControllerUrl);
    }
        
    public function testAction()
    {
    	$this->loadLeftMenu();
    }
    
    private function loadLeftMenu()
    {
    	// Code xu ly bien cho _left_menu.phtml o day
		$this->_helper->layout()->tpl_LeftContent = $this->view->render('user/_left_menu.phtml');
    }

}