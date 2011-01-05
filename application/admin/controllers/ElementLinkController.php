<?php

class Admin_ElementLinkController extends Digitalus_Controller_Action
{
	private $_objElements = null;
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
		parent::init();
		$this->_objElements = new Model_Elements();	
			
    }

    public function indexAction()
    {
    	$this->_helper->layout()->disableLayout();
    	
    	$this->view->isAdmin = $this->_request->getParam('admin',0);
    	
    	$objContents = new Model_Contents();
    	$rsContents = $objContents->fetchAll();
    	$this->view->rsContents = $rsContents;

//		$this->view->rsElements = $this->_objElements->fetchAll();
//    	$this->view->strPagingElements  = $this->createPaginator($this->view->rsElements);		
    }
    
    public function ajaxElementLinkAction()
    {
    	$this->_helper->layout()->disableLayout();
    	// Disable for this action
		$this->_helper->viewRenderer->setNoRender();
		
		$isAdmin = $this->_request->getParam('admin',0);
		
		$condition = null;
		if(!$isAdmin)
			$condition = "module_name != 'admin'";
	
		$this->view->rsElements = $this->_objElements->fetchAll($condition);
		$this->view->containerId = 'container_content';
    	$this->view->strPagingElements = $this->createPaginator($this->view->rsElements,'page','ajax_paging.phtml');
    	//    	$content = $this->view->partial('element-link/_elements_link.phtml', array('rsElements' => $rsElements,'strPagingElements' => $strPagingElements));
    	$content = $this->view->render('element-link/_elements_link.phtml');
    	$this->getResponse()->setBody($content);
    }
    
    public function addAction()
    {
		$val = array();   	
        //you must validate that the session ids match
        if ($this->_request->isPost()) 
        {
        	if($this->_objParams->insertFromPost())
        	{
				$this->clearCache();
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}        	
        	$val = $this->_request->getParams();
        }
		
		$rowParam = $this->_objParams->createRow($val);
		$this->view->rowParam = $rowParam;
		$this->view->title_action = $this->view->getTranslation('Add');
    }
    
    public function editAction()
    {
        if ($this->_request->isPost()) 
        {
//        	$_POST['active'] = isset($_POST['active'])?1:0;
//        	$_POST['active'] = intval($_POST['active']);
        	if($this->_objParams->updateFromPost())
        	{
				$this->clearCache();
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}
        	$rowParam = $this->_objParams->createRow($this->_request->getParams());
        }
        else 
        {
	    	$id = $this->_request->getParam('id');
	    	$rowParam = $this->_objParams->findFormCache($id,$id)->current();
	    	if(!$rowParam)
				$this->_redirect($this->_currentControllerUrl);
        }
		$this->view->rowParam = $rowParam;
		$this->view->title_action = $this->view->getTranslation('Edit');

    }    
    
    public function deleteAction()
    {
		$id = $this->_request->getParam('id');
		if(!is_null($id))
		{
			$this->_objParams->find($id)->current()->delete();			
			$this->clearCache();
		}
			
		$this->_redirect($this->_currentControllerUrl);    	
    	
    }    
    
    public function deleteAllAction()
    {
		$varCheckBoxList = $this->_request->getPost('checkbox');    	
		if(!is_null($varCheckBoxList))
		{
			foreach ($varCheckBoxList as $varID)
				$this->_objParams->find($varID)->current()->delete();			
			$this->clearCache();
		}
		$this->_redirect($this->_currentControllerUrl);
    }    

    public function switchStatusAction()
    {
    	$id = $this->_request->getParam('id');
    	$status = 0;
    	if($this->_objParams->find($id)->current())
    	{
    		$status = $this->_objParams->switchStatus($id,'active');
			$this->clearCache();
    	}
//		$this->_redirect($this->_currentControllerUrl);
		echo $status;
		exit();
    }
    
    private function clearCache()
    {
   		// Xoa toan bo cache database cua model nay
   		$this->_objParams->removeAllDataFormCache();
   		// Xoa toan bo cache content trong controller nay
		$this->_cacheManager->removeCacheFormPath($this->_currentControllerUrl);    	
    }

}