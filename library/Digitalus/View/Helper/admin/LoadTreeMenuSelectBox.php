<?php
class Digitalus_View_Helper_Admin_LoadTreeMenuSelectBox
{
	/*
		$name => Ten cua selectbox
		$FirstItem => ID goc muon load
		$selected => Chon gia tri trong selectbox
		$attribsSelect => Thuoc tinh cua selectbox
		$subtractId => load tru id nay ra (nhanh nay cung nhanh con cua no se bi loai bo trong wa trinh load)
						dung khi tao tree select box cho update action
	*/
	public function loadTreeMenuSelectBox($name,$FirstItem = null,$selected = null,$attribsSelect = null,$subtractId = null)
	{
    	$objMenus = new Model_Menus();
		$FirstItem = ($FirstItem == null ? 0 : $FirstItem);
    	$menuLevelOnes = $objMenus->fetchAll("parent = {$FirstItem}", "order ASC");
    	
    	$options = array();
    	if($FirstItem == 0)
			$options[0] = "/ ROOT"; 
		else 
			$options[0] = $objMenus->fetchRow("menu_id = {$FirstItem}")->name; 
		
		$this->_loadMenuTree($menuLevelOnes,$options,1,$subtractId);
		
		$view = new Zend_View();
		return $view->formSelect($name, $selected, $attribsSelect, $options); 
	}
	
    function _loadMenuTree($argMenuList,&$argArrMenu,$argLevel,$subtractId)
    {
    	$objMenus = new Model_Menus();
    	foreach ($argMenuList as $objMenu)
    	{
    		$len = strlen($objMenu->name);
    		$str = str_pad("|__ " . $objMenu->name,$len + ($argLevel * 6), "-" ,STR_PAD_LEFT);
    		$argArrMenu[$objMenu->menu_id] = $str;
    		if($objMenu->menu_id == $subtractId) return;
			$objMenuNextLevel = $objMenus->fetchAll("parent = {$objMenu->menu_id}", "order ASC");
			$this->_loadMenuTree($objMenuNextLevel,$argArrMenu,$argLevel + 1,$subtractId);
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