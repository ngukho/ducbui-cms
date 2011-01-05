<?php

class MenuPositions extends Digitalus_Db_Table
{
    protected $_name = 'site_menu_positions';
   	protected $_primaryKey = 'menu_position_id'; 
    protected $_rowClass = 'MenuPosition';    
    
    public function __construct()
    {
        parent::__construct();
    }    
        
    public function getList()
    {
    	return $this->fetchAll('active = 1');
    }
}

class MenuPosition extends Zend_Db_Table_Row_Abstract 
{ 
    
    
} 