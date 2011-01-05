<?php
class Digitalus_View_Helper_Admin_RenderAdminMainMenu
{
    public $userModel;
    public $currentUser;
	
    public function RenderAdminMainMenu()
    {
        $this->userModel = new Model_User();
        $this->currentUser = $this->userModel->getCurrentUser();
        
   		$cache = Digitalus_Cache_Manager::getInstance();
		$key = 'admin_main_menu_' . $this->currentUser->role;
		// Lay du lieu tu cache ra
		if(($html = $cache->loadCache($key)) != false) {
			return $html;
		}

        
        $objMenus = new Model_Menus();
        $rsRootLevel = $objMenus->fetchAll('parent = 0 AND active = 1','order ASC');
        
        $html = null;
        foreach ($rsRootLevel as $rowRootLevel)
        {
        	$sub_menu_html = null;
			$rsNextLevel = $rowRootLevel->findDependentRowset($rowRootLevel->getTableClass(),null,$rowRootLevel->select()->where('active = 1')->order('order ASC'));	
			if(count($rsNextLevel) > 0)		
				$sub_menu_html = self::RenderAdminMenu($rsNextLevel,0);

			if(is_null($sub_menu_html) && empty($rowRootLevel->admin_menu_link)) continue;
							
        	$href = 'javascript:void(0);';
        	if(!empty($rowRootLevel->admin_menu_link)) $href = $rowRootLevel->admin_menu_link;
        	$html .= "<li><a href='{$href}' class='menulink'>{$rowRootLevel->name}</a>{$sub_menu_html}</li>";
        }
        if(!is_null($html)) $html = "<div id='main_menu'><ul class='menu' id='menu'>{$html}</ul></div>";
        
        // Luu du lieu vao cache
        $cache->saveCache($html,$key);
        return $html;        
    }

    public function RenderAdminMenu($rsCurrentLevel,$level)
    {
        $html = null;
        $first_row = true;
        
        foreach ($rsCurrentLevel as $rowCurrentLevel)
        {
        	// Canh bao : neu admin_menu_link = null thi sao ?
        	if(!$this->hasAccess($rowCurrentLevel->admin_menu_link)) continue;
        	
        	$rsNextLevel = $rowCurrentLevel->findDependentRowset($rowCurrentLevel->getTableClass(),null,$rowCurrentLevel->select()->where('active = 1')->order('order ASC'));	
        	if(count($rsNextLevel) > 0)
        	{
        		$sub_menu_html = self::RenderAdminMenu($rsNextLevel,$level+1);
        		if(is_null($sub_menu_html)) continue;
	        	$html .= "<li><a href='javascript:void(0)' class='sub'>{$rowCurrentLevel->name}</a>{$sub_menu_html}</li>";
        	}
        	else 
        	{
	        	if($first_row && $level != 0)
	        	{
	        		$html .= "<li class='topline'><a href='{$rowCurrentLevel->admin_menu_link}'>{$rowCurrentLevel->name}</a></li>";
	        		$first_row = false;
	        	}
	        	else 
	        	{
	        		$html .= "<li><a href='{$rowCurrentLevel->admin_menu_link}'>{$rowCurrentLevel->name}</a></li>";
	        	}
        	}
        }
        if(is_null($html)) return null;
        return "<ul>{$html}</ul>";
    }


    public function hasAccess($tab)
    {
        if ($this->currentUser) 
        {
            if ($this->currentUser->role == Model_User::SUPERUSER_ROLE) {
                return true;
            } else
            {
            	$tab = Digitalus_Toolbox_String::stripLeading("/",$tab);
            	$tab = str_replace("/","_",$tab);
            	if ($this->userModel->queryPermissions($tab)) {
                	return true;
            	}
            }
        }
    }

    /**
     * Set this->view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_DeclareVars
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }


}