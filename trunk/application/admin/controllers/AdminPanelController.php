<?php

class Admin_AdminPanelController extends Digitalus_Controller_Action
{
	private $_objAdminPanels = null;
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
		parent::init();
		$this->_objAdminPanels = new Model_AdminPanels();
    }

    public function indexAction()
    {
    	$s_field = $this->_request->getParam('s_field','order');
    	$s_type = $this->_request->getParam('s_type');
    	
    	$rsAdminPanels = $this->_objAdminPanels->fetchAllData(NULL,$s_field,$s_type);
    	
    	$this->view->rsAdminPanels = $rsAdminPanels;
    	
    	// Headers column
    	$this->view->titleTX = $this->view->SortColumn('Title','title',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->orderTX = $this->view->SortColumn('Order','order',$s_type,$this->_currentActionUrl,$s_field);
    }
    
    public function addAction()
    {
		$val = array('order'=>0);   	

        if ($this->_request->isPost()) 
        {
        	$_POST['alias'] = str_replace(' ','-',strtolower(trim($_POST['title'])));
        	if($this->_objAdminPanels->insertFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}        	
        	$val = $this->_request->getParams();
        }
		
		$rowAdminPanel = $this->_objAdminPanels->createRow($val);
		$this->view->rowAdminPanel = $rowAdminPanel;
		$this->view->title_action = "Add";
    }
    
    public function editAction()
    {
        if ($this->_request->isPost()) 
        {
        	$_POST['alias'] = str_replace(' ','-',strtolower(trim($_POST['title'])));
        	if($this->_objAdminPanels->updateFromPost())
        	{
        		// Xoa toan bo cache trong trong troller nay
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}
        	$rowAdminPanel = $this->_objAdminPanels->createRow($this->_request->getParams());
        }
        else 
        {
	    	$id = $this->_request->getParam('id');
	    	$rowAdminPanel = $this->_objAdminPanels->find($id)->current();
	    	if(!$rowAdminPanel)
				$this->_redirect($this->_currentControllerUrl);
        }
		$this->view->rowAdminPanel = $rowAdminPanel;
		$this->view->title_action = "Edit";

    }    
    
    public function deleteAction()
    {
		$id = $this->_request->getParam('id');
		if(!is_null($id))
		{
			$this->_objAdminPanels->find($id)->current()->delete();			
		}
			
		$this->_redirect($this->_currentControllerUrl);    	
    	
    }    
    
    public function deleteAllAction()
    {
		$varCheckBoxList = $this->_request->getPost('checkbox');    	
		if(!is_null($varCheckBoxList))
		{
			foreach ($varCheckBoxList as $varID)
				$this->_objAdminPanels->find($varID)->current()->delete();			
		}
		$this->_redirect($this->_currentControllerUrl);
    }    

    public function switchStatusAction()
    {
    	$id = $this->_request->getParam('id');
    	$status = 0;
    	if($this->_objAdminPanels->find($id)->current())
    	{
    		$status = $this->_objAdminPanels->switchStatus($id,'active');
    	}
//		$this->_redirect($this->_currentControllerUrl);
		echo $status;
		exit();
    }
    
    public function saveOrderAction()
    {
    	$this->_objAdminPanels->saveOrder($_POST['order']);
    	exit();
    }    

}