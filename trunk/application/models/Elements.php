<?php

class Model_Elements extends Digitalus_Db_Table
{
    protected $_name = 'core_elements';
   	protected $_primaryKey = 'element_id'; 
    protected $_rowClass = 'Model_Element';   
    
    public function __construct()
    {
        parent::__construct();
    }    
}

class Model_Element extends Zend_Db_Table_Row_Abstract 
{ 
    
    
} 