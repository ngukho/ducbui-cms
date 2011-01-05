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
 * @package   Digitalus_Core_Library
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: Auth.php Tue Dec 25 20:16:55 EST 2007 20:16:55 forrest lyman $
 */

class Digitalus_Controller_Plugin_Admin_Auth extends Zend_Controller_Plugin_Abstract
{
    /**
     * the current user's identity
     *
     * @var zend_db_row
     */
    private $_identity;

    /**
     * the acl object
     *
     * @var zend_acl
     */
    private $_acl;

    /**
     * the page to direct to if there is a current
     * user but they do not have permission to access
     * the resource
     *
     * @var array
     */
    private $_noacl = array('module' => 'admin',
                             'controller' => 'error',
                             'action' => 'no-auth');

    /**
     * the page to direct to if there is not current user
     *
     * @var unknown_type
     */
    private $_noauth = array('module' => 'admin',
                             'controller' => 'auth',
                             'action' => 'login');


    /**
     * validate the current user's request
     *
     * @param zend_controller_request $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
		$this->_identity = Digitalus_Auth::getIdentity();    	
		
        $controller = $request->controller;
        $module = $request->module;
        $controller = $controller;
        $action = $request->action;

        //go from more specific to less specific
        $moduleLevel = $module;
        $controllerLevel = $moduleLevel . '_' . $controller;
        $actionLevel = $controllerLevel . '_' . $action;
    	
        if ($module != 'public' && $controller != 'public') {
        	// Cac module quan ly admin
	        if (!$this->_identity) 
	        {
	        	// Chua dang nhap
            	$request->setModuleName($this->_noauth['module']);
            	$request->setControllerName($this->_noauth['controller']);
            	$request->setActionName($this->_noauth['action']);
            	$request->setParam('authPage', 'login');
            	return;
	        }
	        
			$this->_acl = new Digitalus_Acl();
			$role = $this->_identity->role;
				
	        if ($this->_acl->has($actionLevel)) {
	            $resource = $actionLevel;
	        } elseif ($this->_acl->has($controllerLevel)) {
	            $resource = $controllerLevel;
	        } else {
	            $resource = $moduleLevel;
	        }
		        
			if ($this->_acl->has($resource) && !$this->_acl->isAllowed($role, $resource)) {
               	$request->setModuleName($this->_noacl['module']);
               	$request->setControllerName($this->_noacl['controller']);
               	$request->setActionName($this->_noacl['action']);
               	$request->setParam('authPage', 'noauth');					
			}

        } else {
			// Day la module public
		}

    	
    }
}