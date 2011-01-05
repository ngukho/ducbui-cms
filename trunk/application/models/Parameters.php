<?php

class Model_Parameters extends Digitalus_Db_Table
{
    protected $_name = 'core_parameters';
   	protected $_primaryKey = 'parameter_id'; 
    protected $_rowClass = 'Model_Parameter';    
    
    protected $_required = array('name','value');
    protected $_unique = array('name');
    
    public function __construct()
    {
        parent::__construct();
    }        
    
    public static final function getParam($key)
    {
    	$objParams = new self();
    	$rowParam = $objParams->fetchRow("name = '{$key}'");
    	return $rowParam->active == 1 ? trim($rowParam->value) : '';
    }    

}

class Model_Parameter extends Zend_Db_Table_Row_Abstract 
{ 
    
    
} 