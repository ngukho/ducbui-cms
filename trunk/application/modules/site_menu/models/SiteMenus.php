<?php
class SiteMenus extends Digitalus_Db_Table
{
    protected $_name = 'site_menus';
   	protected $_primaryKey = 'menu_id'; 
   	private $_cache_key = 'site_menu';

   	protected $_required = array('name');
   	protected $_text = array('name');
    
    protected $_dependentTables = array('SiteMenus');  
    protected $_referenceMap    = array(                
        'SiteMenus' => array(                               
            'columns'           => array('parent'),   
            'refTableClass'     => 'SiteMenus',            
            'refColumns'        => array('menu_id'),    
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::RESTRICT
        )             
    ); 
    
    public function __construct()
    {
        parent::__construct();
    }        
    
    protected function after()
    {
    	$this->_cache->removeCache($this->_cache_key);
    }
    
    // Khai bao de quy delete cap con
    public function delete($id) 
    {
    	$rsMenusChilds = $this->fetchAll("parent = " . $id );
    	foreach ($rsMenusChilds as $rowMenusChild)
    		$this->delete($rowMenusChild->menu_id);
    	$result = parent::delete('menu_id = ' . $id);
    	// Delete cache
    	$this->_cache->removeCache($this->_cache_key);
    	return $result;
    }

    // Khai bao de quy de cap nhat cac menu cap con
    public function switchMenuStatus($id) 
    {
    	$rowMenu = $this->fetchRow('menu_id = ' . $id);
   		$result = $this->doSwitchMenuStatus($id,(!$rowMenu->active));
   		// Delete cache
   		$this->_cache->removeCache($this->_cache_key);
   		return $result;
    }    
    
    private function doSwitchMenuStatus($id,$status) 
    {
    	$rsMenusChilds = $this->fetchAll("parent = " . $id );
    	foreach ($rsMenusChilds as $rowMenusChild)
    		$this->doSwitchMenuStatus($rowMenusChild->menu_id,$status);    	
    	$rowMenu = $this->fetchRow('menu_id = ' . $id);
   		$rowMenu->active =($status); 
   		return $rowMenu->save();
    }
    
    public function getDataMenu()
    {
    	$arrData = array();

		// Lay du lieu tu cache ra
		if(($arrData = $this->_cache->loadCache($this->_cache_key)) != false) {
			return $arrData;
		}		
    	
        $rsRootLevel = $this->fetchAll('parent = 0 AND active = 1','order ASC');
        
        foreach ($rsRootLevel as $rowRootLevel)
        {
        	$sub_menu = null;
			$rsNextLevel = $rowRootLevel->findDependentRowset($rowRootLevel->getTableClass(),null,$rowRootLevel->select()->where('active = 1')->order('order ASC'));
			if(count($rsNextLevel) > 0)		
				$sub_menu = $this->doGetDataMenu($rsNextLevel,0);

			$arr['menu_id'] = $rowRootLevel->menu_id;
			$arr['name'] = $rowRootLevel->name;
			$arr['admin_menu_link'] = $rowRootLevel->admin_menu_link;
			$arr['sub_menu'] = $sub_menu;
			
			$arrData[$rowRootLevel->menu_id] = $arr;
        }
        
        // Luu du lieu vao cache
        $this->_cache->saveCache($arrData,$this->_cache_key);        
		return $arrData;
    }

	private function doGetDataMenu($rsCurrentLevel,$level)
    {
    	$arrData = array();
        foreach ($rsCurrentLevel as $rowCurrentLevel)
        {
			$arr['menu_id'] = $rowCurrentLevel->menu_id;
			$arr['name'] = $rowCurrentLevel->name;
			$arr['admin_menu_link'] = $rowCurrentLevel->admin_menu_link;
			$arr['sub_menu'] = null;

        	$rsNextLevel = $rowCurrentLevel->findDependentRowset($rowCurrentLevel->getTableClass(),null,$rowCurrentLevel->select()->where('active = 1')->order('order ASC'));	
        	if(count($rsNextLevel) > 0)
        	{
        		$sub_menu = $this->doGetDataMenu($rsNextLevel,$level+1);
				$arr['sub_menu'] = $sub_menu;        		
        	}

			$arrData[$rowCurrentLevel->menu_id] = $arr;        	
        }
    	return $arrData;
    }    
    
    public function getMenus()
    {
        //todo: figure out how to do this with a pure select object
        $sql = 'SELECT DISTINCT parent_id FROM pages';
        $result = $this->_db->fetchAll($sql);
        if ($result) {
            foreach ($result as $row) {
                $ids[] = $row->parent_id;
            }
            return $this->find($ids);
        }
    }

    public function openMenu($menuId = 0, $asRowset = false)
    {
        $menu = array();
        $children = $this->getChildren($menuId);
        if ($children->count() > 0) {
            if ($asRowset == true) {
                return $children;
            } else {
                foreach ($children as $child) {
                    $value = $this->getUrl($child);
                    $key = $this->getLabel($child);
                    $menu[$key] = $value;
                }
            }
        }
        return $menu;
    }

    public function hasMenu($menuId)
    {
        if ($this->hasChildren($menuId)) {
            return true;
        }
    }

    public function getLabel($page)
    {
        if (!is_object($page)) {
            $page = $this->find($page)->current();
        }
        if (!empty($page->label)) {
            return $page->label;
        } else {
            return $page->name;
        }
    }

    public function getUrl($page)
    {
        return '#';
    }

    public function updateMenuItems($ids, $labels, $visibility) {
        if (is_array($ids)) {
            for ($i = 0; $i <= (count($ids) - 1); $i++) {
                $this->updateMenuItem($ids[$i], $labels[$i], $visibility[$i], $i);
            }
        }
    }

    public function updateMenuItem($id, $label, $visibility, $position)
    {
        $page = $this->find($id)->current();
        if ($page) {
            $page->label = $label;
            $page->show_on_menu = $visibility;
            $page->position = $position;
            return $page->save();
        }
        return false;
    }
}