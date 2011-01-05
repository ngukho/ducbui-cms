<?php

class Virtuemart_Models_Manufacturers extends Digitalus_Db_Table
{
    protected $_name = 'vm_manufacturers';
   	protected $_primaryKey = 'manu_id'; 
    
    protected $_dependentTables = array('Virtuemart_Models_Products');  

    protected $_required = array('name');
    
    public function __construct()
    {
        parent::__construct();
    }        
    
	function beforeInsert()
	{
		$this->equalsNow('date_added');
	}
	
	function beforeUpdate()
	{
		$this->equalsNow('last_modified');		
	}	    
	
}
