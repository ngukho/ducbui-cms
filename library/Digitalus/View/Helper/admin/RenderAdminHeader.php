<?php
class Digitalus_View_Helper_Admin_RenderAdminHeader
{
	
    public function RenderAdminHeader()
    {
        $userModel = new Model_User();
        $currentUser = $userModel->getCurrentUser();
        
		$cache = Digitalus_Cache_Manager::getInstance();
		$key = 'admin_header_' . $currentUser->user_id;
		if( ($data = $cache->loadCache($key)) != false) {
			return $data;
		}
		
        $this->view->addScriptPath(BASE_TEMPLATES);
        $this->view->header = array('currentUser' => $currentUser);
        $data = $this->view->render('header.phtml');
        
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