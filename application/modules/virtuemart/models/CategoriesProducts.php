<?php

class Virtuemart_Models_CategoriesProducts extends Digitalus_Db_Table
{
    protected $_name = 'vm_products_to_categories';

    protected $_referenceMap    = array(
        'Virtuemart_Models_Products' => array(
            'columns'           => array('product_id'),
            'refTableClass'     => 'Virtuemart_Models_Products',
            'refColumns'        => array('product_id')
        ),
        'Virtuemart_Models_Categories' => array(
            'columns'           => array('cat_id'),
            'refTableClass'     => 'Virtuemart_Models_Categories',
            'refColumns'        => array('cat_id')
        )
    );
    
    public function __construct()
    {
        parent::__construct();
    }        
    
}
