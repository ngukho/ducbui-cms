<?php

require_once('./application/modules/virtuemart/models/Categories.php');

class Virtuemart_View_Helper_ShowSelectCats
{
	/*
		$name => Ten cua selectbox
		$FirstItem => ID goc muon load
		$selected => Chon gia tri trong selectbox
		$attribsSelect => Thuoc tinh cua selectbox
		$subtractId => load tru id nay ra (nhanh nay cung nhanh con cua no se bi loai bo trong wa trinh load)
						dung khi tao tree select box cho update action
	*/	
	
	private $_objCats = null;
	private $_arrCats = null;
	
    public function showSelectCats($name,$firstItem = null,$selected = null,$attribsSelect = null)
    {
		$this->_objCats = new Virtuemart_Models_Categories();    	
		$this->_arrCats = $this->_objCats->getDataCategories();
		
    	$options = array();
    	if(!is_null($firstItem))
    		$options[0] = $firstItem; 
		
		$this->_loadCats($this->_arrCats,$options,1);
		
		return $this->view->formSelect($name, $selected, $attribsSelect, $options); 		
    }
    
    private function _loadCats($arrCats,&$argArrCat,$argLevel)
    {
    	if(is_null($arrCats)) return;
    	foreach ($arrCats as $cat)
    	{
    		$len = strlen($cat['name']);
    		$str = str_pad("|__ " . $cat['name'],$len + ($argLevel * 6), "-" ,STR_PAD_LEFT);
    		$argArrCat[$cat['cat_id']] = $str;
			$this->_loadCats($cat['sub_cat'],$argArrCat,$argLevel + 1);
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