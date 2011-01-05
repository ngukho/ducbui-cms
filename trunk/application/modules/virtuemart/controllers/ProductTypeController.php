<?php

class Mod_Virtuemart_ProductTypeController extends Digitalus_Controller_Action
{

	private $_objProTypes = null;
	
    public function init()
    {
    	parent::init();
		// Load model trong bat ky module nao
    	$this->_objProTypes = $this->loadModel('Virtuemart_Models_ProductTypes');
    }

    public function indexAction()
    {
		$this->loadLeftPage();
    	$s_field = $this->_request->getParam('s_field','name');
    	$s_type = $this->_request->getParam('s_type');
    	
    	$rsProTypes = $this->_objProTypes->fetchAllData(NULL,$s_field,$s_type);
    	
    	$this->view->rsProTypes = $rsProTypes;
    	
    	// Headers column
    	$this->view->nameTX = $this->view->SortColumn('Type Name','name',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->handlerTX = $this->view->SortColumn('Handler Name','type_handler',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->allowAddTX = $this->view->SortColumn('Add To Cart','allow_add_to_cart',$s_type,$this->_currentActionUrl,$s_field);
    }
    
    public function addAction()
    {
		$this->loadLeftPage();    	
		$val = array('allow_add_to_cart' => 1);   	
        //you must validate that the session ids match
        if ($this->_request->isPost()) 
        {
        	if(Digitalus_Filesystem_File::isUploaded('field_default_image'))
        	{
        		
        		// Xu ly upload file
    			$upload = new Digitalus_Resource_Image();
    			$ext = Digitalus_Filesystem_File::getFileExtension($_FILES['field_default_image']['name']);
    			$file_name = 'type_'.time().".{$ext}";
    			$upload->uploadImage('field_default_image',$file_name,DIR_VM_PRODUCT_TYPE_IMAGE,false,false);
    			$_POST['default_image'] = DIR_VM_PRODUCT_TYPE_IMAGE . "/{$file_name}";
        	}
        	
        	if($this->_objProTypes->insertFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}        	
        	$val = $this->_request->getParams();
        }
		
		$rowType = $this->_objProTypes->createRow($val);
		$this->view->rowType = $rowType;
		$this->view->title_action = "Add";
    }
    
    public function editAction()
    {
		$this->loadLeftPage();    	
        if ($this->_request->isPost()) 
        {
        	if(Digitalus_Filesystem_File::isUploaded('field_default_image'))
        	{
        		// Xu ly upload file
    			$upload = new Digitalus_Resource_Image();
    			$ext = Digitalus_Filesystem_File::getFileExtension($_FILES['field_default_image']['name']);
    			$file_name = 'type_'.time().".{$ext}";
    			$upload->uploadImage('field_default_image',$file_name,DIR_VM_PRODUCT_TYPE_IMAGE,false,false);
    			$_POST['default_image'] = DIR_VM_PRODUCT_TYPE_IMAGE . "/{$file_name}";
        	}
        	
        	if($this->_objProTypes->updateFromPost())
        	{
        		// Xoa toan bo cache trong trong troller nay
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}
        	$rowType = $this->_objProTypes->createRow($this->_request->getParams());
        }
        else 
        {
	    	$id = $this->_request->getParam('id');
	    	$rowType = $this->_objProTypes->find($id)->current();
	    	if(!$rowType)
				$this->_redirect($this->_currentControllerUrl);
        }
		$this->view->rowType = $rowType;
		$this->view->title_action = "Edit";

    }    
    
    public function deleteAction()
    {
		$id = $this->_request->getParam('id');
		$currentRow = $this->_objProTypes->find($id)->current();
		if($currentRow)
		{
			if(!empty($currentRow->default_image))
				unlink(DIR_UPLOAD_MEDIA . '/' . $currentRow->default_image);
			$currentRow->delete();			
		}
		$this->_redirect($this->_currentControllerUrl);    	
    }    
    
    public function deleteAllAction()
    {
		$varCheckBoxList = $this->_request->getPost('checkbox');    	
		if(!is_null($varCheckBoxList))
		{
			foreach ($varCheckBoxList as $varID)
			{
				$currentRow = $this->_objProTypes->find($varID)->current();
				if(!empty($currentRow->default_image))
					unlink(DIR_UPLOAD_MEDIA . '/' . $currentRow->default_image);				
				$currentRow->delete();			
			}
		}
		$this->_redirect($this->_currentControllerUrl);
    }    

    public function deleteImageAction()
    {
    	$id = $this->_request->getParam('id');
    	$currentRow = $this->_objProTypes->find($id)->current();
    	if($currentRow)
    	{
			unlink(DIR_UPLOAD_MEDIA . '/' . $currentRow->default_image);
    		$this->_objProTypes->update(array('default_image' => '','last_modified' => time()), 'type_id = ' . $id);
    	}
		$this->_redirect($this->_currentControllerUrl . "/edit/id/{$id}");
    }
    
    private function loadLeftPage()
    {
    	$this->view->id = $this->_request->getParam('id',0);
    	$this->view->rsProTypes = $this->_objProTypes->fetchAll();
    	// Code xu ly bien cho _left_page.phtml o day
		$this->_helper->layout()->tpl_LeftContent = $this->view->render('product-type/_left_page.phtml');
    }    

}