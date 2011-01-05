<?php

require_once('./application/modules/virtuemart/models/Categories.php');

class Virtuemart_View_Helper_GetBreadCrumbsData
{
	
//    public function getBreadCrumbsData($cPath)
//    {
////    	$cPath = '33_34_40';
//		$objCats = new Vm_Categories();    	
//		$subCats = $objCats->getDataCategories();
//		$cPath = explode('_',$cPath);
//
//		$data = array();
//		foreach ($cPath as $id)
//		{
//			if(!isset($subCats[$id]['name'])) return $data;
//			$arr['cat_id'] = $subCats[$id]['cat_id'];
//			$arr['name'] = $subCats[$id]['name'];
//			$arr['image'] = $subCats[$id]['image'];
//			$subCats = $subCats[$id]['sub_cat'];
//			$data[] = $arr;
//		}
//		return $data;
//    }

	private $_data = array();

    public function getBreadCrumbsData($cId)
    {
//    	$cPath = '33_34_40';
		$objCats = new Virtuemart_Models_Categories();    	
		$subCats = $objCats->getDataCategories();

		foreach ($subCats as $subCat)
		{
			$arr = $this->_loadData($subCat,$cId);
			if(!empty($arr))
    		{
				// Bo phan tu vao dau cua mang
   				array_unshift($this->_data,$arr);
    		}
		}
		return $this->_data;
    }
    
    private function _loadData($subCats,$cId)
    {
    	if($subCats['cat_id'] == $cId)
			return $subCats;
    	else 
    	{
    		if(empty($subCats['sub_cat'])) return array();
			foreach ($subCats['sub_cat'] as $subCat)
			{
				$arr = $this->_loadData($subCat,$cId);	
	    		if(!empty($arr))
	    		{ 
	    			// Bo phan tu vao dau cua mang
    				array_unshift($this->_data,$arr);
    				return $subCats;
	    		}
			}    		
    	}
    }
    
    /**
     * Set this->view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_DeclareVars
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }        
}