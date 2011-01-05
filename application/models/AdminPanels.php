<?php

class Model_AdminPanels extends Digitalus_Db_Table
{
    protected $_name = 'core_admin_panels';
   	protected $_primaryKey = 'admin_panel_id'; 
    protected $_rowClass = 'Model_AdminPanel';    
    
    protected $_text = array('title');
    
    protected $_dependentTables = array('Model_Menus');

}

class Model_AdminPanel extends Zend_Db_Table_Row_Abstract 
{ 
    
    
} 