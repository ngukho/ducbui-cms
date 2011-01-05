<?php

class Mod_Virtuemart_ManufacturerController extends Digitalus_Controller_Action
{
	private $_objManus = null;
	
    public function init()
    {
    	parent::init();
		// Load model trong bat ky module nao
		$this->_objManus = $this->loadModel('Virtuemart_Models_Manufacturers');
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
    	$s_field = $this->_request->getParam('s_field');
    	$s_type = $this->_request->getParam('s_type');
    	
    	$rsManus = $this->_objManus->fetchAllData(NULL,$s_field,$s_type);
    	
    	$this->view->strPaging  = $this->createPaginator($rsManus);
    	$this->view->rsManus = $rsManus;
    	
    	// Headers column
    	$this->view->nameTX = $this->view->SortColumn($this->view->getTranslation('Name'),'name',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->urlTX = $this->view->SortColumn($this->view->getTranslation('Url'),'url',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->url_clickedTX = $this->view->SortColumn($this->view->getTranslation('Clicked'),'url_clicked',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->date_last_clickTX = $this->view->SortColumn($this->view->getTranslation('Date Last Click'),'date_last_click',$s_type,$this->_currentActionUrl,$s_field);
    }
    
    public function addAction()
    {
		$val = array();   	
		
        //you must validate that the session ids match
        if ($this->_request->isPost()) 
        {
        	if(Digitalus_Filesystem_File::isUploaded('field_image'))
        	{
        		
        		// Xu ly upload file
    			$upload = new Digitalus_Resource_Image();
    			$ext = Digitalus_Filesystem_File::getFileExtension($_FILES['field_image']['name']);
    			$file_name = 'manu_'.time().".{$ext}";
    			$upload->uploadImage('field_image',$file_name,DIR_VM_MANUFACTURER_IMAGE,false,false);
    			$_POST['image'] = DIR_VM_MANUFACTURER_IMAGE . "/{$file_name}";
        	}        	
        	
        	if($this->_objManus->insertFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}        	
        	$val = $this->_request->getParams();
        }
		
		$rowManu = $this->_objManus->createRow($val);
		$this->view->rowManu = $rowManu;
		$this->view->title_action = $this->view->getTranslation('Add');
    }
    
    public function editAction()
    {
        if ($this->_request->isPost()) 
        {
        	if(Digitalus_Filesystem_File::isUploaded('field_image'))
        	{
        		// Xu ly upload file
    			$upload = new Digitalus_Resource_Image();
    			$ext = Digitalus_Filesystem_File::getFileExtension($_FILES['field_image']['name']);
    			$file_name = 'manu_'.time().".{$ext}";
    			$upload->uploadImage('field_image',$file_name,DIR_VM_MANUFACTURER_IMAGE,false,false);
    			$_POST['image'] = DIR_VM_MANUFACTURER_IMAGE . "/{$file_name}";
        	}        	
        	
        	if($this->_objManus->updateFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}
        	$rowManu = $this->_objManus->createRow($this->_request->getParams());
        }
        else 
        {
	    	$id = $this->_request->getParam('id');
	    	$rowManu = $this->_objManus->find($id)->current();
	    	if(!$rowManu)
				$this->_redirect($this->_currentControllerUrl);
        }
		$this->view->rowManu = $rowManu;
		$this->view->title_action = $this->view->getTranslation('Edit');
    }    
    
    public function deleteAction()
    {
		$id = $this->_request->getParam('id');
		if(!is_null($id))
		{
			$this->_objManus->find($id)->current()->delete();			
		}
			
		$this->_redirect($this->_currentControllerUrl);    	
    }    
    
    public function deleteAllAction()
    {
		$varCheckBoxList = $this->_request->getPost('checkbox');    	
		if(!is_null($varCheckBoxList))
		{
			foreach ($varCheckBoxList as $varID)
				$this->_objManus->find($varID)->current()->delete();			
		}
		$this->_redirect($this->_currentControllerUrl);
    }    
    
    public function deleteImageAction()
    {
    	$id = $this->_request->getParam('id');
    	$currentRow = $this->_objManus->find($id)->current();
    	if($currentRow)
    	{
			unlink(DIR_UPLOAD_MEDIA . '/' . $currentRow->image);
    		$this->_objManus->update(array('image' => '','last_modified' => time()), 'manu_id = ' . $id);
    	}
		$this->_redirect($this->_currentControllerUrl . "/edit/id/{$id}");
    }    

}