<?php

class Virtuemart_Models_ProductTypesCategories extends Digitalus_Db_Table
{
    protected $_name = 'vm_product_types_to_categories';
    
    protected $_referenceMap    = array(
        'Virtuemart_Models_ProductTypes' => array(
            'columns'           => array('type_id'),
            'refTableClass'     => 'Virtuemart_Models_ProductTypes',
            'refColumns'        => array('type_id')
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
