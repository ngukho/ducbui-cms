<?php

class Admin_ContentController extends Digitalus_Controller_Action
{
	private $_objContents = null;
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
		parent::init();
		$this->_objContents = new Model_Contents();
    }

    public function indexAction()
    {
		$this->loadLeftPage();
    	$s_field = $this->_request->getParam('s_field');
    	$s_type = $this->_request->getParam('s_type');
    	
    	$rsContents = $this->_objContents->fetchAllData(NULL,$s_field,$s_type);
    	
    	$this->view->rsContents = $rsContents;
    	
    	// Headers column
    	$this->view->titleTX = $this->view->SortColumn('Title Content','title',$s_type,$this->_currentActionUrl,$s_field);
    }
    
    public function addAction()
    {
		$this->loadLeftPage();    	
		$val = array();   	
        //you must validate that the session ids match
        if ($this->_request->isPost()) 
        {
        	if($this->_objContents->insertFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}        	
        	$val = $this->_request->getParams();
        	
//			echo "<pre>";
//			print_r('dgdfgdfg');
//			echo "</pre>";
//			exit();
//
//			$convert = new Digitalus_Convert();
//			
//			$title = $convert->scriptToData($_POST['title']);
//			
//        	$this->_objContents->insert(array('title' => title,'html' => $_POST['html']));
//        	$this->_redirect($this->_currentControllerUrl);
//        	return;
        }
		
		$rowContent = $this->_objContents->createRow($val);
		$this->view->rowContent = $rowContent;
		$this->view->title_action = "Add";
    }
    
    public function editAction()
    {
		$this->loadLeftPage();    	
        if ($this->_request->isPost()) 
        {
        	if($this->_objContents->updateFromPost())
        	{
        		// Xoa toan bo cache trong trong troller nay
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}
        	$rowContent = $this->_objContents->createRow($this->_request->getParams());

//			$convert = new Digitalus_Convert();
//			
//			$title = $convert->scriptToData($_POST['title']);
//			$html = stripslashes($_POST['html']);
//			
//        	$this->_objContents->update(array('title' => $title,'html' => $html),'content_id = ' . $_POST['content_id']);
//        	$this->_redirect($this->_currentControllerUrl);
//        	return;

        }
        else 
        {
	    	$id = $this->_request->getParam('id');
	    	$rowContent = $this->_objContents->find($id)->current();
	    	if(!$rowContent)
				$this->_redirect($this->_currentControllerUrl);
        }
		$this->view->rowContent = $rowContent;
		$this->view->title_action = "Edit";

    }    
    
    public function viewDetailAction()
    {
    	$this->_helper->layout()->disableLayout();
    	// Disable for this action
		$this->_helper->viewRenderer->setNoRender();
		
    	$id = $this->_request->getParam('id');		
		$rowContent = $this->_objContents->find($id)->current();
		// Tra ve html chuan
		$html = Digitalus_Convert::changeDataToScriptEdit($rowContent->html,'editor');
    	$this->getResponse()->setBody($html);    	
    }
    
    public function deleteAction()
    {
		$id = $this->_request->getParam('id');
		if(!is_null($id))
		{
			$this->_objContents->find($id)->current()->delete();			
		}
			
		$this->_redirect($this->_currentControllerUrl);    	
    	
    }    
    
    public function deleteAllAction()
    {
		$varCheckBoxList = $this->_request->getPost('checkbox');    	
		if(!is_null($varCheckBoxList))
		{
			foreach ($varCheckBoxList as $varID)
				$this->_objContents->find($varID)->current()->delete();			
		}
		$this->_redirect($this->_currentControllerUrl);
    }    

    public function switchStatusAction()
    {
    	$id = $this->_request->getParam('id');
    	$status = 0;
    	if($this->_objContents->find($id)->current())
    	{
    		$status = $this->_objContents->switchStatus($id,'active');
    	}
//		$this->_redirect($this->_currentControllerUrl);
		echo $status;
		exit();
    }
    
    private function loadLeftPage()
    {
    	$this->view->id = $this->_request->getParam('id',0);
    	$this->view->rsContents = $this->_objContents->fetchAll();
    	// Code xu ly bien cho _left_page.phtml o day
		$this->_helper->layout()->tpl_LeftContent = $this->view->render('content/_left_page.phtml');
    }    

}