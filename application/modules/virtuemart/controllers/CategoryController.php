<?php

class Mod_VirtueMart_CategoryController extends Digitalus_Controller_Action
{
	private $_objCats  = null;
		
	public function init()
    {
    	parent::init();
    	// Load helper
    	$this->loadHelper();
		// Load model trong bat ky module nao
		$this->_objCats = $this->loadModel('Virtuemart_Models_Categories');
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
    	$this->_redirect($this->_currentModuleUrl);
//		$c_path = $this->_request->getParam('c_path',0);
//		
//    	$s_field = $this->_request->getParam('s_field');
//    	$s_type = $this->_request->getParam('s_type');
//    	
//    	$rsCats = $this->_objCats->fetchAllData('parent_id = ' . $c_path,$s_field,$s_type);

//    	$this->view->strPaging  = $this->createPaginator($rsCats);
//    	$this->view->rsCats = $rsCats;
    	
    	// Headers column
//    	$this->view->nameTX = $this->view->SortColumn($this->view->getTranslation('Name'),'name',$s_type,$this->_currentActionUrl,$s_field);
//    	$this->view->valueTX = $this->view->SortColumn($this->view->getTranslation('Value'),'value',$s_type,$this->_currentActionUrl,$s_field);
//    	$this->view->activeTX = $this->view->SortColumn('Active','active',$s_type,$this->_currentActionUrl,$s_field);
		
		
//    	$arr = $this->_objCats->getDataCategories();
//    	
//    	$this->loadHelper();
//    	
//    	echo "<pre>";
//    	print_r($this->view->showSelectCats('name'));
//    	echo "</pre>";
//    	exit();
    	
    }
    
    public function addAction()
    {
    	$cId = $this->_request->getParam('cId',0);
    	$cPath = $this->_request->getParam('cPath',null);
    	
    	// Neu khong ton tai category thi chuyen den trang chu
    	if($cId != 0 && !$this->_objCats->find($cId)->current())
    	{
    		$this->_redirect($this->_currentModuleUrl);
    	}
    	
		$val = array('parent_id' => $cId,'order' => 0,'active' => 0);   	
		
        //you must validate that the session ids match
        if ($this->_request->isPost()) 
        {
        	if(Digitalus_Filesystem_File::isUploaded('field_image'))
        	{
        		// Xu ly upload file
    			$upload = new Digitalus_Resource_Image();
    			$ext = Digitalus_Filesystem_File::getFileExtension($_FILES['field_image']['name']);
    			$file_name = 'cat_'.time().".{$ext}";
    			$upload->uploadImage('field_image',$file_name,DIR_VM_CATALOG_IMAGE,false,false);
    			$_POST['image'] = DIR_VM_CATALOG_IMAGE . "/{$file_name}";
        	}        	
        	
        	if($this->_objCats->insertFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}        	
        	$val = $this->_request->getParams();
        }
		
   		$this->view->breadCrumbs = $this->view->partial('category-product/_bread_crumbs.phtml', array('view'=>$this->view,'arrPath' => $this->view->getBreadCrumbsData($cId)));
        		
		$rowCat = $this->_objCats->createRow($val);
		$this->view->rowCat = $rowCat;
		$this->view->cId = $cId;
		$this->view->cPath = $cPath;
		$this->view->title_action = $this->view->getTranslation('Add');
    }
    
    public function editAction()
    {
    	$cId = $this->_request->getParam('cId');
    	$cPath = $this->_request->getParam('cPath');
    	
        if ($this->_request->isPost()) 
        {
        	if(Digitalus_Filesystem_File::isUploaded('field_image'))
        	{
        		// Xu ly upload file
    			$upload = new Digitalus_Resource_Image();
    			$ext = Digitalus_Filesystem_File::getFileExtension($_FILES['field_image']['name']);
    			$file_name = 'cat_'.time().".{$ext}";
    			$upload->uploadImage('field_image',$file_name,DIR_VM_CATALOG_IMAGE,false,false);
    			$_POST['image'] = DIR_VM_CATALOG_IMAGE . "/{$file_name}";
        	}        	
        	
        	if($this->_objCats->updateFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}
        	$rowManu = $this->_objCats->createRow($this->_request->getParams());
        }
        else 
        {
	    	$rowCat = $this->_objCats->find($cId)->current();
	    	if(!$rowCat)
				$this->_redirect($this->_currentControllerUrl);
        }
        
		$this->view->breadCrumbs = $this->view->partial('category-product/_bread_crumbs.phtml', array('view'=>$this->view,'arrPath' => $this->view->getBreadCrumbsData($cId)));        
		$this->view->rowCat = $rowCat;
		$this->view->cId = $cId;
		$this->view->cPath = $cPath;
		$this->view->title_action = $this->view->getTranslation('Edit');
    }    
    
    public function switchStatusAction()
    {
    	$cId = $this->_request->getParam('cId');
    	$cPath = $this->_request->getParam('cPath');
    	$currentRow = $this->_objCats->find($cId)->current();
    	if($currentRow)
    	{
    		$status = $this->_objCats->switchStatus($cId,'active');
    	}
		$this->_redirect($this->_currentModuleUrl . "/category-product/index/cPath/{$cPath}/cId/{$currentRow->parent_id}");
    }    
    
    public function deleteImageAction()
    {
    	$cId = $this->_request->getParam('cId');
    	$currentRow = $this->_objCats->find($cId)->current();
    	if($currentRow)
    	{
    		
			unlink(DIR_UPLOAD_MEDIA . '/' . $currentRow->image);
    		$this->_objCats->update(array('image' => '','last_modified' => time()), 'cat_id = ' . $cId);
    	}
		$this->_redirect($this->_currentControllerUrl . "/edit/id/{$id}");
    }        
}