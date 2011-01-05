<?php
class Digitalus_View_Helper_Admin_RenderAdminMainMenu
{
    public $userModel;
    public $currentUser;
	
    public function RenderAdminMainMenu()
    {
        $this->userModel = new Model_User();
        $this->currentUser = $this->userModel->getCurrentUser();
        
    	$objMenu = new Model_Menus();
    	$arrData = $objMenu->getDataMenu();
        
        $html = null;
        foreach ($arrData as $arr)
        {
        	$sub_menu_html = null;
			if(!is_null($arr['sub_menu']))		
				$sub_menu_html = self::RenderAdminMenu($arr['sub_menu'],0);

			if(is_null($sub_menu_html) && empty($arr['admin_menu_link'])) continue;
							
        	$href = 'javascript:void(0);';
        	if(!empty($arr['admin_menu_link'])) $href = BASE_URL . $arr['admin_menu_link'];
        	$html .= "<li><a href='{$href}' class='menulink'>{$arr['name']}</a>{$sub_menu_html}</li>";
        }
        if(!is_null($html)) $html = "<div id='main_menu'><ul class='menu' id='menu'>{$html}</ul></div>";
        
        return $html;        
    }

    public function RenderAdminMenu($arrData,$level)
    {
        $html = null;
        $first_row = true;
        
        foreach ($arrData as $arr)
        {
        	// Canh bao : neu admin_menu_link = null thi sao ?
        	if(!$this->hasAccess($arr['admin_menu_link'])) continue;
        	
        	$href = BASE_URL . $arr['admin_menu_link'];
        	if(!is_null($arr['sub_menu']))
        	{
        		$sub_menu_html = self::RenderAdminMenu($arr['sub_menu'],$level+1);
        		if(is_null($sub_menu_html)) continue;
	        	$html .= "<li><a href='javascript:void(0)' class='sub'>{$arr['name']}</a>{$sub_menu_html}</li>";
        	}
        	else 
        	{
	        	if($first_row && $level != 0)
	        	{
	        		$html .= "<li class='topline'><a href='{$href}'>{$arr['name']}</a></li>";
	        		$first_row = false;
	        	}
	        	else 
	        	{
	        		$html .= "<li><a href='{$href}'>{$arr['name']}</a></li>";
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