<?php

class Model_Groups extends Digitalus_Db_Table
{
    protected $_name = 'core_groups';
   	protected $_primaryKey = 'group_id'; 
    protected $_rowClass = 'Model_Group';
    
    protected $_required = array('group_name');
    protected $_unique = array('group_name');
    
    public function __construct()
    {
        parent::__construct();
    }        

    public function copyPermissions($from, $to)
    {
        $fromGroup = $this->find($from)->current();
        $toGroup = $this->find($to)->current();
        $toGroup->acl_resources = $fromGroup->acl_resources;
        return $toGroup->save();
    }    
}

class Model_Group extends Zend_Db_Table_Row_Abstract 
{ 
    public function updateAclResources($resourceArray)
    {
        $this->acl_resources = serialize($resourceArray);
        return $this->save();
    }

    public function getAclResources()
    {
        return unserialize($this->acl_resources);
    }
    
    
} 