<?php

class Mod_VirtueMart_CategoryProductController extends Digitalus_Controller_Action
{
	private $_objCats  = null;
		
	public function init()
    {
    	parent::init();
    	// Load helper
    	$this->loadHelper();
		// Load model trong bat ky module nao
//		$this->loadModel('Virtuemart_Models_Products');
//		$this->loadModel('Virtuemart_Models_CategoriesProducts');
//		$this->_objCats = $this->loadModel('Virtuemart_Models_Categories');
		$this->_objCats = new Virtuemart_Models_Categories();
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
    	$cId = $this->_request->getParam('cId',0);
		$cPath = $this->_request->getParam('cPath',null);
    	
		// Kiem tra su ton tai cua Category
    	if($cId != 0 && !$this->_isExistRow($cId))
    	{
    		$this->_redirect($this->_currentActionUrl);
    	}
    	
    	$s_field = $this->_request->getParam('s_field','cat_id');
    	$s_type = $this->_request->getParam('s_type',0);

    	// Get all categories
    	$rsCats = $this->_objCats->fetchAllData('parent_id = ' . $cId,$s_field,$s_type);
    	// Get current category
    	$currentCat = $this->_objCats->find($cId)->current();
    	
		$rsProducts = array();
		// Neu la root thi khong can phan trang cua Products
		if($cId != 0 && $currentCat)
		{
			$order_field = $s_field . ($s_type == 1? ' DESC ' : ' ASC ');
			$rsProducts = $currentCat->findManyToManyRowset('Virtuemart_Models_Products', 'Virtuemart_Models_CategoriesProducts',null,null,$currentCat->select()->order($order_field));
	    	$this->view->strPaging  = $this->createPaginator($rsProducts);		
		}
	    	
    	$this->view->rsCats = $rsCats;
    	$this->view->rsProducts = $rsProducts;
    	$this->view->cId = $cId;
    	$this->view->cPath = $cPath;
    	
    	// Headers column
    	$this->view->nameTX = $this->view->SortColumn($this->view->getTranslation('Categories / Products'),'name',$s_type,$this->_currentActionUrl . '/cPath/' . $cPath . '/cId/' . $cId,$s_field);
    	$this->view->orderTX = $this->view->SortColumn($this->view->getTranslation('Order'),'order',$s_type,$this->_currentActionUrl . '/cPath/' . $cPath . '/cId/' . $cId,$s_field);
//    	$this->view->valueTX = $this->view->SortColumn($this->view->getTranslation('Value'),'value',$s_type,$this->_currentActionUrl,$s_field);
//    	$this->view->activeTX = $this->view->SortColumn('Active','active',$s_type,$this->_currentActionUrl,$s_field);

		$this->view->breadCrumbs = $this->view->partial('category-product/_bread_crumbs.phtml', array('view'=>$this->view,'arrPath' => $this->view->getBreadCrumbsData($cId)));
		$this->view->selProductTypes = $this->view->showSelectProducTypes('type_id');

    	
//		$this->view->getBreadCrumbsData(2,4);
		
//		echo "<pre>";
//		print_r($this->view->getBreadCrumbsData(2,4));
//		echo "</pre>";
//		exit();
		
//    	$arr = $this->_objCats->getDataCategories();
//    	

//    	
//    	echo "<pre>";
//    	print_r($this->view->showSelectCats('name',0));
//    	echo "</pre>";
//    	exit();
    	

    	
    }
    
    private function _isExistRow($id)
    {
    	return $this->_objCats->find($id)->current();
    }
}