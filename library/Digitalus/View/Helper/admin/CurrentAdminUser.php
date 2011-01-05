<?php
class  Digitalus_View_Helper_Admin_CurrentAdminUser
{

    /**
     * comments
     */
    public function CurrentAdminUser($id = 'current-user')
    {
        $u = new Model_User();
        $user = $u->getCurrentUser();

        if ($user) {
            $xhtml = "<ul id='{$id}'>
                    <li>Current User : <a href='{$this->view->getBaseUrl()}/admin/user/edit/id/{$user->user_id}'>{$user->first_name} {$user->last_name}</a></li>
                    <li>Role : {$user->role}</li>
                    <li>[<a href='{$this->view->getBaseUrl()}/admin/auth/logout/'>Log Out</a>]</li>
                </ul>";
            return $xhtml;
        } else {
            return false;
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
