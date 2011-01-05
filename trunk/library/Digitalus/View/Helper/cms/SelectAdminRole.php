<?php
class Zend_View_Helper_SelectAdminRole
{
    public function SelectAdminRole($name, $value, $attribs = false)
    {
    	$objGroups = new Model_Groups();
    	$rsGroups = $objGroups->fetchAll(null,'level');
    	
    	$currentUser = Digitalus_Auth::getIdentity();
//    	$group_admin_system = Model_Parameters::getParam('group_admin_system');
    	$group_admin_system = Model_User::SUPERUSER_ROLE;
    	foreach ($rsGroups as $rowGroup)
    	{
   			if($rowGroup->role == $group_admin_system && $currentUser->role != $group_admin_system) continue; 
    		$data[$rowGroup->group_id] = $rowGroup->group_name;
    	}
    	
//        $data['admin']      = $this->view->getTranslation('Site Administrator');
//        $data['superadmin'] = $this->view->getTranslation('Super Administrator');
        
        return $this->view->formSelect($name, $value, $attribs, $data);
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