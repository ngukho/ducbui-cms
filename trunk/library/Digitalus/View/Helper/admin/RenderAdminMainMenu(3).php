<?php
class Digitalus_View_Helper_Admin_RenderAdminMainMenu
{
//	public static $level = null;
	
	
    public function RenderAdminMainMenu()
    {
        $userModel = new Model_User();
        $currentUser = $userModel->getCurrentUser();
        
        $objMenus = new Model_Menus();
        $rsRootLevel = $objMenus->fetchAll('parent = 0','order ASC');
        
        $html = "<div id='main_menu'><ul class='menu' id='menu'>";
        
        foreach ($rsRootLevel as $rowRootLevel)
        {
        	$html .= "<li>";
        	if(empty($rowRootLevel->admin_menu_link))
        		$html .= "<a href='javascript:void(0)' class='menulink'>{$rowRootLevel->name}</a>";
        	else 
        		$html .= "<a href='{$rowRootLevel->admin_menu_link}' class='menulink'>{$rowRootLevel->name}</a>";
        	
			$rsNextLevel = $rowRootLevel->findDependentRowset($rowRootLevel->getTableClass(),null,$rowRootLevel->select()->order('order ASC'));	
			if(count($rsNextLevel) > 0)		
				$html .= self::RenderAdminMenu($rsNextLevel,0);
        	$html .= "</li>";
        }
        $html .= "</ul></div>";
        return $html;
        

    }

    public function RenderAdminMenu($rsCurrentLevel,$level)
    {
        $html = "<ul>";
        $first_row = true;
        
        foreach ($rsCurrentLevel as $rowCurrentLevel)
        {
        	$rsNextLevel = $rowCurrentLevel->findDependentRowset($rowCurrentLevel->getTableClass(),null,$rowCurrentLevel->select()->order('order ASC'));	
        	if(count($rsNextLevel) > 0)
        	{
	        	$html .= "<li>";
	        	$html .= "<a href='javascript:void(0)' class='sub'>{$rowCurrentLevel->name}</a>";
	        	$html .= self::RenderAdminMenu($rsNextLevel,$level +1);
	        	$html .= "</li>";
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
    	$html .= "</ul>";
    	return $html;

    }


    public function hasAccess($tab)
    {
        if ($this->currentUser) {
            if ($this->currentUser->role == Model_User::SUPERUSER_ROLE) {
                return true;
            } elseif ($this->userModel->queryPermissions($tab)) {
                return true;
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