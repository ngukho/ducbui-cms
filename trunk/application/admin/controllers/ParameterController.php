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
//require_once 'Zend/Controller/Action.php';

/**
 * Day la module mau , ap dung cac ky thuat cache trong nay
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @category   Digitalus CMS
 * @package    Digitalus_CMS_Controllers
 * @version    $Id: SiteController.php Tue Dec 25 19:46:11 EST 2007 19:46:11 forrest lyman $
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */
class Admin_ParameterController extends Digitalus_Controller_Action
{
	private $_objParams = null;
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
		parent::init();
		$this->_objParams = new Model_Parameters();		
    }

    /**
     * The default action
     *
     * Render the main site admin interface
     *
     * @return void
     */
    public function indexAction()
    {
    	$s_field = $this->_request->getParam('s_field');
    	$s_type = $this->_request->getParam('s_type');
    	
//    	$rsParams = $this->_objParams->fetchAllData(NULL,$s_field,$s_type);
    	$rsParams = $this->_objParams->fetchAllDataFromCache('all',NULL,$s_field,$s_type);
    	
		// Phan trang theo cach tao query , dung tot cho khoi luong du lieu lon
//    	$rsParams = $this->_objParams->createQuery(null,$s_field,$s_type);
    	
    	$this->view->strPaging  = $this->createPaginator($rsParams);
    	$this->view->rsParams = $rsParams;
    	
    	// Headers column
    	$this->view->nameTX = $this->view->SortColumn($this->view->getTranslation('Name'),'name',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->valueTX = $this->view->SortColumn($this->view->getTranslation('Value'),'value',$s_type,$this->_currentActionUrl,$s_field);
//    	$this->view->activeTX = $this->view->SortColumn('Active','active',$s_type,$this->_currentActionUrl,$s_field);
    	
    }
    
    public function addAction()
    {
		$val = array();   	
		
        //you must validate that the session ids match
        if ($this->_request->isPost()) 
        {
//        	$_POST['active'] = isset($_POST['active'])?1:0; 
//			$_POST['active'] = intval($_POST['active']);
        	if($this->_objParams->insertFromPost())
        	{
				$this->clearCache();
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}        	
        	$val = $this->_request->getParams();
        }
		
		$rowParam = $this->_objParams->createRow($val);
		$this->view->rowParam = $rowParam;
		$this->view->title_action = $this->view->getTranslation('Add');
    }
    
    public function editAction()
    {
        if ($this->_request->isPost()) 
        {
//        	$_POST['active'] = isset($_POST['active'])?1:0;
//        	$_POST['active'] = intval($_POST['active']);
        	if($this->_objParams->updateFromPost())
        	{
				$this->clearCache();
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}
        	$rowParam = $this->_objParams->createRow($this->_request->getParams());
        }
        else 
        {
	    	$id = $this->_request->getParam('id');
	    	$rowParam = $this->_objParams->findFormCache($id,$id)->current();
	    	if(!$rowParam)
				$this->_redirect($this->_currentControllerUrl);
        }
		$this->view->rowParam = $rowParam;
		$this->view->title_action = $this->view->getTranslation('Edit');

    }    
    
    public function deleteAction()
    {
		$id = $this->_request->getParam('id');
		if(!is_null($id))
		{
			$this->_objParams->find($id)->current()->delete();			
			$this->clearCache();
		}
			
		$this->_redirect($this->_currentControllerUrl);    	
    	
    }    
    
    public function deleteAllAction()
    {
		$varCheckBoxList = $this->_request->getPost('checkbox');    	
		if(!is_null($varCheckBoxList))
		{
			foreach ($varCheckBoxList as $varID)
				$this->_objParams->find($varID)->current()->delete();			
			$this->clearCache();
		}
		$this->_redirect($this->_currentControllerUrl);
    }    

    public function switchStatusAction()
    {
    	$id = $this->_request->getParam('id');
    	$status = 0;
    	if($this->_objParams->find($id)->current())
    	{
    		$status = $this->_objParams->switchStatus($id,'active');
			$this->clearCache();
    	}
//		$this->_redirect($this->_currentControllerUrl);
		echo $status;			
		exit();
    }
    
    private function clearCache()
    {
   		// Xoa toan bo cache database cua model nay
   		$this->_objParams->removeAllDataFormCache();
   		// Xoa toan bo cache content trong controller nay
		$this->_cacheManager->removeCacheFormPath($this->_currentControllerUrl);    	
    }

    /**
     * Edit action
     *
     * Update the site settings file
     *
     * @return void
     */
//    public function editAction()
//    {
//        $settings = Digitalus_Filter_Post::raw('setting');
//        $s = new Model_SiteSettings();
//        foreach ($settings as $k => $v) {
//            $s->set($k, $v);
//        }
//        $s->save();
//        $this->_redirect('admin/site');
//    }

    /**
     * Console action
     *
     * The console provides an interface for simple command scripts.
     * those scripts go in library/Digitalus/Command/{script name}
     *
     * @return void
     */
    public function consoleAction()
    {
        //set up a unique id for this session
        $session = new Zend_Session_Namespace('console_session');
        $previousId = $session->id;
        $session->id = md5(time());
        $this->view->consoleSession = $session->id;

        //you must validate that the session ids match
        if ($this->_request->isPost() && !empty($previousId)) {
            $this->view->commandExecuted = true;
            $this->view->command = 'Command: ' . Digitalus_Filter_Post::get('command');
            $this->view->date = time();

            //execute command
            //validate the session

            if (Digitalus_Filter_Post::get('consoleSession') == $previousId) {
                $this->view->lastCommand = Digitalus_Filter_Post::get('command');
                if (Digitalus_Filter_Post::get('runCommand')) {
                   $results = Digitalus_Command::run(Digitalus_Filter_Post::get('command'));
                } elseif (Digitalus_Filter_Post::get('getInfo')) {
                    $results = Digitalus_Command::info(Digitalus_Filter_Post::get('command'));
                } else {
                    $results = array('ERROR: invalid request');
                }
            } else {
                $results[] = 'ERROR: invalid session';
            }

            $this->view->results = $results;
        }

        $breadcrumbLabel = $this->view->getTranslation('Site Console');
        $this->view->breadcrumbs[$breadcrumbLabel] = $this->getFrontController()->getBaseUrl() . '/admin/site/console';
        $this->view->toolbarLinks = array();
        $this->view->toolbarLinks['Add to my bookmarks'] = $this->getFrontController()->getBaseUrl() . '/admin/index/bookmark/url/admin_site_console';

    }

    /**
     * Mail test action
     *
     * @return void
     */
    public function mailTestAction()
    {
        $settings = new Model_SiteSettings();
        $message = new Digitalus_Mail();
        $message->send(
            $settings->get('default_email'),
            array($settings->get('default_email'), $settings->get('default_email_sender')),
            'Digitalus CMS Test Message',
            'test'
        );
        $this->_forward('index');
    }

}