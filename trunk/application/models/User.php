<?php

/**
 * Digitalus CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @category   Digitalus CMS
 * @package    Digitalus_CMS_Models
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: User.php Mon Dec 24 20:38:38 EST 2007 20:38:38 forrest lyman $
 */

class Model_User extends Digitalus_Db_Table
{
    const SUPERUSER_ROLE = 'superadmin';

    protected $_name = 'core_users';
    
   	protected $_primaryKey = 'user_id'; 
    
	protected $_referenceMap = array(
        'Model_Groups' => array(
            'columns'           => 'group_id',
            'refTableClass'     => 'Model_Groups',
            'refColumns'        => 'group_id'
        )
    );    

    protected $_required = array('first_name','last_name','email');
    protected $_email = array('email');
    protected $_unique = array('email');
    protected $_password = array('password',6,'confirm_password');
    
    public function __construct()
    {
        parent::__construct();
    }        
    
    public function updatePassword($user_id, $password, $confirmationRequire = true, $confirmation = null)
    {
    	$errors = Digitalus_View_Error::getInstance();
    	$message = Digitalus_View_Message::getInstance();
        $person = $this->find($user_id)->current();
        if($person) 
        {
            $person->password = md5($password);
            $result = $person->save();
            $message->add('Updated password successfully !');
            return $result;
        } else {
        	$errors->add('User not exists.');
            return false;
        }
    }

    public function updateAclResources($user_id, $resourceArray)
    {
        $data['acl_resources'] = serialize($resourceArray);
        $where[] = $this->_db->quoteInto('user_id = ?', $user_id);
        return $this->update($data, $where);
    }

    public function getAclResources($userRowset)
    {
        return unserialize($userRowset->acl_resources);
    }

    /**
     * returns the complete user row for the currently logged in user
     * @return zend_db_row
     */
    public function getCurrentUser()
    {
        $currentUser = Digitalus_Auth::getIdentity();
        if ($currentUser) {
            return $this->find($currentUser->user_id)->current();
        }
    }


    public function getCurrentUsersAclResources()
    {
        $currentUser = $this->getCurrentUser();
        if ($currentUser) {
            return $this->getAclResources($currentUser);
        }
    }

    public function getCurrentUsersModules()
    {
        return $this->getUsersModules($this->getCurrentUser());
    }

    public function getUsersModules($userRowset)
    {
        $modules = null;
        $user = $this->getCurrentUser();
        if ($user->role == Model_User::SUPERUSER_ROLE) {
//		if ($user->role == Model_Parameters::getParam('group_admin_system')) {        	
            //the superadmin has access to all of the modules
            $front = Zend_Controller_Front::getInstance();
            $ctlPaths = $front->getControllerDirectory();
            foreach ($ctlPaths as $module => $path) {
                if (substr($module, 0, 4) == 'mod_') {
                    $modules[] = str_replace('mod_', '', $module);
                }
            }
        } else {
            $resources = $this->getAclResources($userRowset);
            if (is_array($resources)) {
                foreach ($resources as $k => $v) {
                    if (1 == $v ) {
                        $parts = explode('_', $k);
                        if ('mod' == $parts[0]) {
                            $key = $parts[1];
                            $modules[$key] = $key;
                        }
                    }
                }
            }
        }
        if (is_array($modules)) {
            return $modules;
        }
    }

    /**
     * this function queries a users permissions
     *
     * the resource should be in the module_controller_action format
     *
     * if strict = true then this requires an exact match
     * example: news_article != news_article_edit
     *
     * if strict = false then it will add wildcards
     * example: news_article == news_article_edit
     *
     * if user is not set then the query will be run on the current user
     *
     * @param string $resource
     * @param boolean $strict
     * @param integer $user
     * @return boolean
     */
    public function queryPermissions($resource, $strict = false, $user_id = null)
    {
        if ($user_id !== null) {
            $user = $this->find($user_id)->current();
            if (!$user) {
                return false;
            }
            $resources = $this->getAclResources($user);
        } else {
            $resources = $this->getCurrentUsersAclResources();
        }

        if (is_array($resources)) {
            if ($strict) {
                if (array_key_exists($resource, $resources) && 1 == $resources[$resource]) {
                    return true;
                }
            } else {
                $len = strlen($resource);
                foreach ($resources as $r => $v) {
                    if (1 == $v && $resource == substr($r, 0, $len)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }
    
	public static function hasAccess($path)
    {
    	$userModel = new self;
   	    $user = $userModel->getCurrentUser();
    	
		if ($user->role == Model_User::SUPERUSER_ROLE) {
	       	return true;
        } 
        else {
	       	$path = Digitalus_Toolbox_String::stripLeading("/",$path);
           	$path = str_replace("/","_",$path);
           	if ($userModel->queryPermissions($path)) 
               	return true;
			else 
				return false;
        }
    }    

    public function getUserByUsername($userName)
    {
        $where[] = $this->_db->quoteInto('email = ?', $userName);
        return $this->fetchRow($where);
    }

    /**
     * @since 0.8.7
     *
     * returns a hash of the current users
     * their id is the key and their first_name . ' ' . last_name is the value
     *
     */
    public function getUserNamesArray()
    {
        $users = $this->fetchAll();
        foreach ($users as $user) {
            $usersArray[$user->user_id] = $user->first_name . ' ' . $user->last_name;
        }
        return $usersArray;
    }

    public function copyPermissions($from, $to)
    {
        $fromUser = $this->find($from)->current();
        $toUser = $this->find($to)->current();
        $toUser->acl_resources = $fromUser->acl_resources;
        return $toUser->save();
    }
    
    public function updatePermissionFromGroup($user_id)
    {
    	$user = $this->find($user_id)->current();
    	$objGroups = new Model_Groups();
    	$rowGroup = $objGroups->find($user->group_id)->current();
    	$user->role = $rowGroup->role;
    	$user->acl_resources = $rowGroup->acl_resources;
    	$user->save();
    	return $user;
    	
    }

}
