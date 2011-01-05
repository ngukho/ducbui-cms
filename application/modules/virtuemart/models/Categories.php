<?php

class Virtuemart_Models_Categories extends Digitalus_Db_Table
{
    protected $_name = 'vm_categories';
   	protected $_primaryKey = 'cat_id'; 
   	private $_cache_key = 'vm_categories';
    
    protected $_dependentTables = array('Virtuemart_Models_Categories','Virtuemart_Models_Products');  
    protected $_referenceMap    = array(                
        'Virtuemart_Models_Category' => array(                               
            'columns'           => array('parent_id'),   
            'refTableClass'     => 'Virtuemart_Models_Categories',            
            'refColumns'        => array('cat_id'),    
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::RESTRICT
        )
    ); 

    protected $_required = array('name');
    protected $_text = array('name','metatags_title','metatags_keywords','metatags_description');
    protected $_HTML = array('description');
//    protected $_HTML = array('body');    
//    protected $_email = array('email');
//    protected $_unique = array('email');

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

	// Lay toan bo categories luu vao trong mang de de xu ly
    public function getDataCategories()
    {
    	$arrData = array();

		// Lay du lieu tu cache ra
		if(($arrData = $this->_cache->loadCache($this->_cache_key)) != false) {
			return $arrData;
		}		
    	
        $rsRootLevel = $this->fetchAll('parent_id = 0','order ASC');
        
        foreach ($rsRootLevel as $rowRootLevel)
        {
        	$sub_menu = null;
			$rsNextLevel = $rowRootLevel->findDependentRowset($rowRootLevel->getTableClass(),null,$rowRootLevel->select()->order('order ASC'));
			
			if(count($rsNextLevel) > 0)		
				$sub_menu = $this->doGetDataCategories($rsNextLevel,0);

			$arr['cat_id'] = $rowRootLevel->cat_id;
			$arr['name'] = $rowRootLevel->name;
			$arr['image'] = $rowRootLevel->image;
			$arr['order'] = $rowRootLevel->order;
			$arr['active'] = $rowRootLevel->active;
			$arr['sub_cat'] = $sub_menu;
			
			$arrData[$rowRootLevel->cat_id] = $arr;
        }
        
        // Luu du lieu vao cache
        $this->_cache->saveCache($arrData,$this->_cache_key);        
		return $arrData;
    }

	private function doGetDataCategories($rsCurrentLevel,$level)
    {
    	$arrData = array();
        foreach ($rsCurrentLevel as $rowCurrentLevel)
        {
			$arr['cat_id'] = $rowCurrentLevel->cat_id;
			$arr['name'] = $rowCurrentLevel->name;
			$arr['image'] = $rowCurrentLevel->image;
			$arr['order'] = $rowCurrentLevel->order;
			$arr['active'] = $rowCurrentLevel->active;
			$arr['sub_cat'] = null;

        	$rsNextLevel = $rowCurrentLevel->findDependentRowset($rowCurrentLevel->getTableClass(),null,$rowCurrentLevel->select()->order('order ASC'));	
        	if(count($rsNextLevel) > 0)
        	{
        		$sub_menu = $this->doGetDataCategories($rsNextLevel,$level+1);
				$arr['sub_cat'] = $sub_menu;        		
        	}

			$arrData[$rowCurrentLevel->cat_id] = $arr;        	
        }
    	return $arrData;
    }    
	
	
    
}
