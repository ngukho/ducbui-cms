<?php

require_once('./application/modules/virtuemart/models/Manufacturers.php');

class Virtuemart_View_Helper_ShowSelectManufacturers
{
	/*
		$name => Ten cua selectbox
		$FirstItem => ID goc muon load
		$selected => Chon gia tri trong selectbox
		$attribsSelect => Thuoc tinh cua selectbox
	*/	
	
    public function ShowSelectManufacturers($name,$firstItem = null,$selected = null,$attribsSelect = null)
    {
		$objManus = new Virtuemart_Models_Manufacturers();
		$rsManus = $objManus->fetchAll();
		
    	$options = array();
    	if(!is_null($firstItem))
    		$options[0] = $firstItem; 

		foreach ($rsManus as $rowManu)
			$options[$rowManu->manu_id] = $this->view->h($rowManu->name); 
		
		return $this->view->formSelect($name, $selected, $attribsSelect, $options); 		
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