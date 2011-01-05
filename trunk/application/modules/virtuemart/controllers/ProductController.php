<?php

class Mod_VirtueMart_ProductController extends Digitalus_Controller_Action
{
	private $_objCats  = null;
	private $_objProducts  = null;
	
		
	public function init()
    {
    	parent::init();
    	// Load helper
    	$this->loadHelper();    	
		// Load model trong bat ky module nao
		$this->_objProducts = $this->loadModel('Virtuemart_Models_Products');		
		$this->loadModel('Virtuemart_Models_CategoriesProducts');
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
//
////    	$this->view->strPaging  = $this->createPaginator($rsCats);
//    	$this->view->rsCats = $rsCats;
//    	
//    	// Headers column
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
    	$type_id = $this->_request->getParam('type_id',null);
    	
    	// Neu khong ton tai category hay product thi chuyen den trang chu
    	if($cId != 0 && !$this->_objCats->find($cId)->current())
    	{
    		$this->_redirect($this->_currentModuleUrl);
    	}    	

		$val = array(
			'tax_rates_id' => 0,
			'quantity' => 0,
			'price' => 0,
			'discount' => 0,
			'weight' => 0,
			'in_stock' => 0,
			'quantity_order_min' => 0,
			'quantity_order_max' => 0,
			'quantity_order_units' => 0,
			'is_virtual' => 0,
			'is_free' => 0,
			'is_call' => 0,
			'is_always_free_shipping' => 0,
			'order' => 0,
			'active' => 0);   	
		
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
        		
		$rowProduct = $this->_objProducts->createRow($val);
		
		$this->view->rowProduct = $rowProduct;
		$this->view->cId = $cId;
		$this->view->cPath = $cPath;
		$this->view->type_id = $type_id;
		$this->view->title_action = $this->view->getTranslation('Add');    	

    }
}