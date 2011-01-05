<?php

class Mod_Virtuemart_TaxRateController extends Digitalus_Controller_Action
{

	private $_objTaxRates = null;
	
    public function init()
    {
    	parent::init();
		// Load model trong bat ky module nao
    	$this->_objTaxRates = $this->loadModel('Virtuemart_Models_TaxRates');
    }

    public function indexAction()
    {
		$this->loadLeftPage();
    	$s_field = $this->_request->getParam('s_field','name');
    	$s_type = $this->_request->getParam('s_type');
    	
    	$rsTaxRates = $this->_objTaxRates->fetchAllData(NULL,$s_field,$s_type);
    	
    	$this->view->rsTaxRates = $rsTaxRates;
    	
    	// Headers column
    	$this->view->nameTX = $this->view->SortColumn('Name','name',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->taxRateTX = $this->view->SortColumn('Tax Rate','tax_rate',$s_type,$this->_currentActionUrl,$s_field);
    }
    
    public function addAction()
    {
		$this->loadLeftPage();    	
		$val = array('order' => 0 , 'active' => 0);   	
        //you must validate that the session ids match
        if ($this->_request->isPost()) 
        {
        	if($this->_objTaxRates->insertFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}        	
        	$val = $this->_request->getParams();
        }
		
		$rowTaxRate = $this->_objTaxRates->createRow($val);
		$this->view->rowTaxRate = $rowTaxRate;
		$this->view->title_action = "Add";
    }
    
    public function editAction()
    {
		$this->loadLeftPage();    	
        if ($this->_request->isPost()) 
        {
        	if($this->_objTaxRates->updateFromPost())
        	{
        		// Xoa toan bo cache trong trong troller nay
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}
        	$rowType = $this->_objTaxRates->createRow($this->_request->getParams());
        }
        else 
        {
	    	$id = $this->_request->getParam('id');
	    	$rowTaxRate = $this->_objTaxRates->find($id)->current();
	    	if(!$rowTaxRate)
				$this->_redirect($this->_currentControllerUrl);
        }
		$this->view->rowTaxRate = $rowTaxRate;
		$this->view->title_action = "Edit";
    }    
    
    public function deleteAction()
    {
		$id = $this->_request->getParam('id');
		$currentRow = $this->_objTaxRates->find($id)->current();
		if($currentRow)
			$currentRow->delete();			
		$this->_redirect($this->_currentControllerUrl);    	
    }    
    
    public function deleteAllAction()
    {
		$varCheckBoxList = $this->_request->getPost('checkbox');    	
		if(!is_null($varCheckBoxList))
		{
			foreach ($varCheckBoxList as $varID)
			{
				$currentRow = $this->_objTaxRates->find($varID)->current();
				if($currentRow)
					$currentRow->delete();			
			}
		}
		$this->_redirect($this->_currentControllerUrl);
    }    

    private function loadLeftPage()
    {
    	$this->view->id = $this->_request->getParam('id',0);
    	$this->view->rsTaxRates = $this->_objTaxRates->fetchAll();
    	// Code xu ly bien cho _left_page.phtml o day
		$this->_helper->layout()->tpl_LeftContent = $this->view->render('tax-rate/_left_page.phtml');
    }    

}