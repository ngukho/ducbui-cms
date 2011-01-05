<?php

class Virtuemart_Models_Products extends Digitalus_Db_Table
{
    protected $_name = 'vm_products';
   	protected $_primaryKey = 'product_id'; 
    
//    protected $_dependentTables = array('Virtuemart_Models_Categories','Virtuemart_Models_Products');  
    protected $_referenceMap    = array(                
        'Virtuemart_Models_Categories' => array(                               
            'columns'           => array('master_cat_id'),   
            'refTableClass'     => 'Virtuemart_Models_Categories',            
            'refColumns'        => array('cat_id'),    
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::RESTRICT
        ),     
        'Virtuemart_Models_Manufacturers' => array(                               
            'columns'           => array('manu_id'),   
            'refTableClass'     => 'Virtuemart_Models_Manufacturers',            
            'refColumns'        => array('manu_id'),    
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::RESTRICT
        ),     
        'Virtuemart_Models_ProductTypes' => array(                               
            'columns'           => array('type_id'),   
            'refTableClass'     => 'Virtuemart_Models_ProductTypes',            
            'refColumns'        => array('type_id'),    
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::RESTRICT
        )             
    ); 

    protected $_required = array('name','master_cat_id');
    protected $_text = array('name','model','metatags_title,','metatags_keywords','metatags_description');
    protected $_HTML = array('description');
    protected $_number = array('quantity','price','tax','weight','in_stock','quantity_order_min','quantity_order_max','quantity_order_units');
    
//    protected $_email = array('email');
//    protected $_unique = array('email');
    
    public function __construct()
    {
        parent::__construct();
    }        
    
	function beforeInsert()
	{
		$_POST['date_added'] = time();
	}
	
	function beforeUpdate()
	{
		$_POST['last_modified'] = time();
	}	    
    
}
