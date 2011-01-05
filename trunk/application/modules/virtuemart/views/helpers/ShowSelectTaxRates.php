<?php

require_once('./application/modules/virtuemart/models/TaxRates.php');

class Virtuemart_View_Helper_ShowSelectTaxRates
{
	/*
		$name => Ten cua selectbox
		$FirstItem => ID goc muon load
		$selected => Chon gia tri trong selectbox
		$attribsSelect => Thuoc tinh cua selectbox
	*/	
	
    public function ShowSelectTaxRates($name,$firstItem = null,$selected = null,$attribsSelect = null)
    {
		$objTaxRates = new Virtuemart_Models_TaxRates();
		$rsTaxRates = $objTaxRates->fetchAll();
		
    	$options = array();
    	if(!is_null($firstItem))
    		$options[0] = $firstItem; 

		foreach ($rsTaxRates as $rowTaxRate)
			$options[$rowTaxRate->tax_rate_id] = $this->view->h($rowTaxRate->name); 
		
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