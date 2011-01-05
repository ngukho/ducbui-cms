<?php
class Digitalus_View_Helper_Admin_RenderListIcons
{

    public function RenderListIcons($dir,$name)
    {
		$cache = Digitalus_Cache_Manager::getInstance();
		$key = "{$dir}/$name";
		if( ($data = $cache->loadCache($key)) != false) {
			return $data;
		}
		$arr_files = Digitalus_Filesystem_File::getFilesByType($dir,array('png','jpg','gif'));
		$dir = Digitalus_Toolbox_String::stripLeading(BASE_PATH,$dir);
		
		$data = "&nbsp;<input type='radio' value='' name='{$name}'/>&nbsp;Empty&nbsp;";
		foreach ($arr_files as $icon_file)
		{
			$data .= "&nbsp;<span><input type='radio' value='{$icon_file}' name='admin_menu_img'/>&nbsp;";
			$data .= "<img src='{$dir}/{$icon_file}' alt='{$icon_file}'/></span>&nbsp;";
		}
		
		$cache->saveCache($data,$key);
        return $data;        
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
