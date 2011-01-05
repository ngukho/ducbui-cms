<?php

class Model_EmailTemplates extends Digitalus_Db_Table
{
    protected $_name = 'core_email_templates';
   	protected $_primaryKey = 'email_template_id'; 
    protected $_rowClass = 'Model_EmailTemplate';    
    
    protected $_required = array('name');
    protected $_unique = array('name');
    protected $_text = array('name','subject','note');
    protected $_HTML = array('body');
    
    public function __construct()
    {
        parent::__construct();
    }    
    
//    
//    public static final function getParam($key)
//    {
//    	$objParams = new self();
//    	$rowParam = $objParams->fetchRow("name = '{$key}'");
//    	return $rowParam->active == 1 ? trim($rowParam->value) : '';
//    }    

}

class Model_EmailTemplate extends Zend_Db_Table_Row_Abstract 
{ 
    
    
} 