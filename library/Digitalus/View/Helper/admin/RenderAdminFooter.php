<?php
class Digitalus_View_Helper_Admin_RenderAdminFooter
{
	
    public function RenderAdminFooter()
    {
//        $this->userModel = new Model_User();
//        $this->currentUser = $this->userModel->getCurrentUser();
		$cache = Digitalus_Cache_Manager::getInstance();
		$key = 'admin_footer';
		if( ($data = $cache->loadCache($key)) != false) {
			return $data;
		}
		
        $this->view->addScriptPath(BASE_TEMPLATES);
        $data = $this->view->render('footer.phtml');
        
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