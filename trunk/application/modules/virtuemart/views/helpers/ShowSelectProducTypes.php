<?php

require_once('./application/modules/virtuemart/models/ProductTypes.php');

class Virtuemart_View_Helper_ShowSelectProducTypes
{
	/*
		$name => Ten cua selectbox
		$FirstItem => ID goc muon load
		$selected => Chon gia tri trong selectbox
		$attribsSelect => Thuoc tinh cua selectbox
	*/	
	
    public function ShowSelectProducTypes($name,$firstItem = null,$selected = null,$attribsSelect = null)
    {
		$objPTypes = new Virtuemart_Models_ProductTypes();
		$rsPTypes = $objPTypes->fetchAll();
		
    	$options = array();
    	if(!is_null($firstItem))
    		$options[0] = $firstItem; 

		foreach ($rsPTypes as $rowPType)    			
			$options[$rowPType->type_id] = $this->view->h($rowPType->name); 
		
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