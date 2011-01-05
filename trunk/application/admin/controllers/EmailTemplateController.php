<?php

class Admin_EmailTemplateController extends Digitalus_Controller_Action
{
	private $_objETemps = null;
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
		parent::init();
		$this->_objETemps = new Model_EmailTemplates();
    }

    public function indexAction()
    {
    	$s_field = $this->_request->getParam('s_field');
    	$s_type = $this->_request->getParam('s_type');
    	
    	$rsETemps = $this->_objETemps->fetchAllData(NULL,$s_field,$s_type);
    	
    	$this->view->rsETemps = $rsETemps;
    	
    	// Headers column
    	$this->view->nameTX = $this->view->SortColumn('Name','name',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->subjectTX = $this->view->SortColumn('Subject','subject',$s_type,$this->_currentActionUrl,$s_field);
    }
    
    public function addAction()
    {
		$val = array();   	
        //you must validate that the session ids match
        if ($this->_request->isPost()) 
        {
//        	$_POST['active'] = isset($_POST['active'])?1:0; 
//			$_POST['active'] = intval($_POST['active']);
        	if($this->_objETemps->insertFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}        	
        	$val = $this->_request->getParams();
        }
		
		$rowETemp = $this->_objETemps->createRow($val);
		$this->view->rowETemp = $rowETemp;
		$this->view->title_action = "Add";
    }
    
    public function editAction()
    {
        if ($this->_request->isPost()) 
        {
//        	$_POST['active'] = isset($_POST['active'])?1:0;
//        	$_POST['active'] = intval($_POST['active']);
        	if($this->_objETemps->updateFromPost())
        	{
        		// Xoa toan bo cache trong trong troller nay
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}
        	$rowETemp = $this->_objETemps->createRow($this->_request->getParams());
        }
        else 
        {
	    	$id = $this->_request->getParam('id');
	    	$rowETemp = $this->_objETemps->find($id)->current();
	    	if(!$rowETemp)
				$this->_redirect($this->_currentControllerUrl);
        }
		$this->view->rowETemp = $rowETemp;
		$this->view->title_action = "Edit";

    }    
    
    public function deleteAction()
    {
		$id = $this->_request->getParam('id');
		if(!is_null($id))
		{
			$this->_objETemps->find($id)->current()->delete();			
		}
			
		$this->_redirect($this->_currentControllerUrl);    	
    	
    }    
    
    public function deleteAllAction()
    {
		$varCheckBoxList = $this->_request->getPost('checkbox');    	
		if(!is_null($varCheckBoxList))
		{
			foreach ($varCheckBoxList as $varID)
				$this->_objETemps->find($varID)->current()->delete();			
		}
		$this->_redirect($this->_currentControllerUrl);
    }    

    public function switchStatusAction()
    {
    	$id = $this->_request->getParam('id');
    	$status = 0;
    	if($this->_objETemps->find($id)->current())
    	{
    		$status = $this->_objETemps->switchStatus($id,'active');
    	}
//		$this->_redirect($this->_currentControllerUrl);
		echo $status;
		exit();
		
    }

}