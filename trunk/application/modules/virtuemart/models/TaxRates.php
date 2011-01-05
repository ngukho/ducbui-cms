<?php

require_once('./application/modules/virtuemart/models/products.php');

class Virtuemart_Models_TaxRates extends Digitalus_Db_Table
{
    protected $_name = 'vm_tax_rates';
   	protected $_primaryKey = 'tax_rate_id'; 
    
    protected $_dependentTables = array('Virtuemart_Models_Products');  

    protected $_required = array('name','tax_rate');
//    protected $_email = array('email');
//    protected $_unique = array('email');
    
    public function __construct()
    {
        parent::__construct();
    }        
    
    public function before()
    {
    	// Chua su dung toi
//    	$type_handler = preg_replace("/\s*[-\s]+\s*/", "_", $_POST['name']);
//    	$this->equalsValue('type_handler',$type_handler);
    }
    
	public function beforeInsert()
	{
		//$this->_data['date_added'] = time(); 
		$this->equalsValue('date_added',time());
	}
	
	public function beforeUpdate()
	{
		//$this->_data['date_added'] = time(); 
		$this->equalsValue('last_modified',time());
	}	        
    
}
