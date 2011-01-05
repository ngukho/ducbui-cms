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
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id:$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */

/** Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';

/**
 * Admin Auth Conroller of Digitalus CMS
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @category   Digitalus CMS
 * @package    Digitalus_CMS_Controllers
 * @version    $Id: AuthController.php Mon Dec 24 20:48:35 EST 2007 20:48:35 forrest lyman $
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */
class Admin_AuthController extends Digitalus_Controller_Action
{

    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
    	parent::init();
//        $this->view->breadcrumbs = array(
//           $this->view->getTranslation('Login') => $this->getFrontController()->getBaseUrl() . '/admin/auth/login'
//        );
    }

    /**
     * Login action
     *
     * if the form has not been submitted this renders the login form
     * if it has then it validates the data
     * if it is sound then it runs the Digitalus_Auth_Adapter function
     * to authorise the request
     * on success it redirect to the admin home page
     *
     * @return void
     */
    public function loginAction()
    {
    	// Neu dang nhap roi thi chuyen den trang chu
    	if(Digitalus_Auth::getIdentity()) $this->_redirect('admin');
    	
        if ($this->_request->isPost()) {
            $uri = Digitalus_Filter_Post::get('uri');
            $uri = str_replace(BASE_URL."/","",$uri);
            
            $username = Digitalus_Filter_Post::get('username');
            $password = Digitalus_Filter_Post::raw('password');

            if ($username == '') {
                $this->_errors->add('You must enter a username.');
            }
            if ($password == '') {
            	$this->_errors->add('You must enter a password.');
            }


            if (!$this->_errors->hasErrors()) {
                $auth = new Digitalus_Auth($username, $password);
                $result = $auth->authenticate();
                if ($result) {
                    if ($uri == '' || $uri == 'admin/auth/login') {
                        $uri = 'admin';
                    }
                     $this->_redirect($uri);
                } else {
                    $this->_errors->add('The username or password you entered was not correct.');
                }
            }
            $this->view->uri = $uri;
        } else {
//            $this->view->uri = Digitalus_Uri::get();
            $this->view->uri = $_SERVER['REQUEST_URI'];
        }
		$this->_helper->layout->setLayout('login');
		$this->_cacheManager->doNotCache(true);
    }

    /**
     * Login action
     *
     * kills the authorized user object
     * then redirects to the main index page
     *
     * @return void
     */
    public function logoutAction()
    {
        Digitalus_Auth::destroy();
        $this->_redirect('/');
    }
    
    
    /*
    	Tam dung ham nay de load toan bo ACL Path vao database
    */
    public function loadElementPathAction()
    {
        $front = Zend_Controller_Front::getInstance();
        $ctlPaths = $front->getControllerDirectory();

        $objElements = new Model_Elements();
        
        //set the path to all of the modules
        foreach ($ctlPaths as $module => $path)  
        {
	        $module_acl = new Model_ModuleAcls($module);
            $xml = $module_acl->toXml();
    		if(empty($xml)) continue;
                    
            $controllers = $xml->children();
            foreach ($controllers as $controller) 
            {
	                $controllerName = (string)$controller->attributes()->name;
                    $controllerActions = $controller->children();
                    if (count($controllerActions) > 0) 
                    {
                    	foreach ($controllerActions as $action) 
                    	{
                        	//load each action separately
                            $actionName = (string)$action;
                            $key = $module . '_' . $controllerName . '_' . $actionName;
                            $path = '/' . $module . '/' . $controllerName . '/' . $actionName;
                            $arr = array(
                            	'module_name' => $module,
                            	'key' => $key,
                            	'alias_path' => $path,
                            	'role' => Model_user::SUPERUSER_ROLE,
                            	'created_day' => time(),
                            	'active' => 1
                            );
                            $objElements->insert($arr);
                        }
                    } 
                    else 
                    {
                        $key = $module . '_' . $controllerName;
                        $path = '/' . $module . '/' . $controllerName;
                        $arr = array(
                           	'module_name' => $module,
                           	'key' => $key,
                           	'alias_path' => $path,
                           	'role' => MOdel_User::SUPERUSER_ROLE,
                           	'created_day' => time(),
                           	'active' => 1
                        );
                        $objElements->insert($arr);
                    }
            }
        }    
        
        echo "<pre>";
       	print_r('Updated successfully !!');
       	echo "</pre>";
       	exit();	
    }

    /**
     * Reset password action
     *
     * @return void
     */
//    public function resetPasswordAction()
//    {
//        if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
//            $email = Digitalus_Filter_Post::get('email');
//            $user = new Model_User();
//            $match = $user->getUserByUsername($email);
//            if ($match) {
//                //create the password
//                $password = Digitalus_Toolbox_String::random(10); //10 character random string
//
//                //load the email data
//                $data['first_name'] = $match->first_name;
//                $data['last_name'] = $match->last_name;
//                $data['username'] = $match->email;
//                $data['password'] = $password;
//
//                //get standard site settings
//                $s = new Model_SiteSettings();
//                $settings = $s->toObject();
//
//                //attempt to send the email
//                $mail = new Digitalus_Mail();
//                if ($mail->send($match->email, array($sender), 'Password Reminder', 'passwordReminder', $data)) {
//                    //update the user's password
//                    $match->password = md5($password);
//                    $match->save();//save the new password
//                    $m = new Digitalus_View_Message();
//                    $m->add(
//                        $this->view->getTranslation('Your password has been reset for security and sent to your email address')
//                       );
//                } else {
//                    $e = new Digitalus_View_Error();
//                    $e->add(
//                        $this->view->getTranslation('Sorry, there was an error sending you your updated password.  Please contact us for more help.')
//                       );
//                }
//            } else {
//                $e = new Digitalus_View_Error();
//                $e->add(
//                    $this->view->getTranslation('Sorry, we could not locate your account. Please contact us to resolve this issue.')
//                );
//            }
//            $url =  'admin/auth/login';
//            $this->_redirect($url);
//         }
//    }

}