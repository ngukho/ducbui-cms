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
 * Admin Index Conroller of Digitalus CMS
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @category   Digitalus CMS
 * @package    Digitalus_CMS_Controllers
 * @version    $Id: IndexController.php Mon Dec 24 20:50:29 EST 2007 20:50:29 forrest lyman $
 * @link       http://www.digitaluscms.com
 * @since      Release 1.0.0
 */
class Admin_IndexController extends Digitalus_Controller_Action
{
    public $userModel;
    public $currentUser;

    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
    	parent::init();
        $this->userModel = new Model_User();
        $this->currentUser = $this->userModel->getCurrentUser();
    }

    /**
     * The default action
     *
     * Displays the admin dashboard
     *
     * @return void
     */
    public function indexAction()
    {
//    	$menu = new Model_Menus();
//    	$arr = $menu->getDataMenu();
//    	
//    	
//    	echo "<pre>";
//    	print_r($arr);
//    	echo "</pre>";
//    	exit();
    	
    	
    	
		$key = 'admin_panel_' . $this->currentUser->role;
		// Lay du lieu tu cache ra
//		if(($content = $this->_cacheManager->loadCache($key)) != false) {
//			$this->view->indexContent = $content;
//			return;
//		}

    	$objAdminPanels = new Model_AdminPanels();
    	$rsAdminPanels = $objAdminPanels->fetchAll('active = 1','order ASC');
    	
		$content = "";    	
		foreach ($rsAdminPanels as $rowAdminPanel) 
		{
			$rsMenus = $rowAdminPanel->findDependentRowset('Model_Menus',null,$rowAdminPanel->select()->where('active = 1'));	
			if(count($rsMenus) <= 0) continue;
			
			$html = null;
			foreach ($rsMenus as $rowMenu)
			{
				// Canh bao : neu admin_menu_link = null thi sao ?
//				if(!$this->hasAccess($rowMenu->admin_menu_link)) continue;
				if(!Model_User::hasAccess($rowMenu->admin_menu_link)) continue;
				$html .= $this->view->partial('index/_panel_item.phtml', array('view'=>$this->view,'rowMenu' => $rowMenu));				
			}
			
			if(is_null($html)) continue;
			$title = $this->view->partial('index/_panel_title.phtml', array('view'=>$this->view,'rowAdminPanel' => $rowAdminPanel));
			$content .= "{$title}<div id='cpanel'>{$html}<div class='clear'></div></div>";
		}
		
		$this->view->indexContent = $content;
       	// Luu du lieu vao cache
//        $this->_cacheManager->saveCache($content,$key);
    }

	private function hasAccess($path)
    {
        if ($this->currentUser) 
        {
            if ($this->currentUser->role == Model_User::SUPERUSER_ROLE) {
                return true;
            } else
            {
            	
            	$path = Digitalus_Toolbox_String::stripLeading("/",$path);
            	$path = str_replace("/","_",$path);
            	if ($this->userModel->queryPermissions($path)) {
                	return true;
            	}
            }
        }
    }
    
    public function bak_indexAction()
    {
    	$objAdminPanels = new Model_AdminPanels();
    	$rsAdminPanels = $objAdminPanels->fetchAll('active = 1','order ASC');
    	
		$content = "";    	
		foreach ($rsAdminPanels as $rowAdminPanel) 
		{
			$rsMenus = $rowAdminPanel->findDependentRowset('Model_Menus',null,$rowAdminPanel->select()->where('active = 1'));	
			if(count($rsMenus) <= 0) continue;
			$content .= $this->view->partial('index/_panel.phtml', array('rowAdminPanel' => $rowAdminPanel,'rsMenus' => $rsMenus));		 
		}
		
		$this->view->indexContent = $content;
    	
    }
    
    
    /**
     * Notes action
     *
     * @return void
     */
    public function notesAction()
    {
        $notes = new Model_Note();
        $myNotes = Digitalus_Filter_Post::get('content');
        $notes->saveUsersNotes($myNotes);
        $this->_redirect('admin/index');
    }

    /**
     * Bookmark action
     *
     * @return void
     */
    public function bookmarkAction()
    {
        $url = $this->_request->getParam('url');
        $label = $this->_request->getParam('label', $url);
        // the bookmark links are set up so if you dont have js enabled it will just use a default value for you
        // this makes it pass an array as the label if you do set it, so we need to fetch the last item if it is an array
        if(is_array($label)) {
            $label = array_pop($label);
        }
        $bookmark = new Model_Bookmark();
        $bookmark->addUsersBookmark($label, $url);
    }

    /**
     * Delete bookmark action
     *
     * @return void
     */
    public function deleteBookmarkAction()
    {
        $id = $this->_request->getParam('id');
        $bookmark = new Model_Bookmark();
        $bookmark->deleteBookmark($id);
        $this->_redirect('admin/index');
    }

        /**
     * Test action
     *
     * @return void
     */
    public function mainAction(){
		$v = new Digitalus_Convert();    	
		
    	$string="Joe's \"dinner\"";
    	
		$data = $v->scriptToData($string);
		
		$view = $v->dataToScriptView($data);
		
		$edit = $v->dataToScriptEdit($data);
		
		echo "<pre>";
		print_r($data . " -- DATA");
		echo "</pre>";
		
		echo "<br>";
		
		echo "<pre>";
		print_r($view . " -- VIEW");
		echo "</pre>";
		
		echo "<br>";
		
		echo "<pre>";
		print_r($edit . " -- EDIT");
		echo "</pre>";			
		
		exit();
    	
	}
    
    /**
     * Test action
     *
     * @return void
     */
    public function testAction()
    {
    	$str = "test  --------  test";
    	$str = preg_replace("/\s*[-\s]+\s*/", "_", $str);

    	echo "<pre>";
    	print_r($str);
    	echo "</pre>";
    	exit();    	
    	
//    	html_entity_decode();

//  		$convert = Digitalus_Convert::removeAccent("Bùi Văn Tiến Đức");
    		

//		$objhtmlEntities 	= new Zend_Filter_HtmlEntities();


		$v = new Digitalus_Convert();
		
		$str = '<img align="left" style="" width="" height="" class="border_img" src="/media/image/images_2.jpeg" alt="" />';

		
//		&lt;p&gt;&lt;img height=\&quot;150\&quot; width=\&quot;100\&quot; src=\&quot;/media/image/images_2.jpeg\&quot; alt=\&quot;\&quot; /&gt;&lt;/p&gt;
//		&lt;p&gt;&lt;img height=\&quot;150\&quot; width=\&quot;100\&quot; src=\&quot;/media/image/images_2.jpeg\&quot; alt=\&quot;\&quot; /&gt;&lt;/p&gt;
//		&lt;img align=&quot;left&quot; style=&quot;&quot; width=&quot;&quot; height=&quot;&quot; class=&quot;border_img&quot; src=&quot;/media/image/images_2.jpeg&quot; alt=&quot;&quot; /&gt;

		$ss = "&lt;p&gt;&lt;img height=\&quot;150\&quot; width=\&quot;100\&quot; src=\&quot;/media/image/images_2.jpeg\&quot; alt=\&quot;\&quot; /&gt;&lt;/p&gt;";

		// Nguyen goc
		print_r($str);
		
		
		// Luu vao database
		$str_1 = $v->scriptToData($str,'editor'); 
		print_r($str_1);
		
		


		// Outputs: A 'quote' is &lt;b&gt;bold&lt;/b&gt;
//		$str = htmlentities($str);
//		$str = $objhtmlEntities->filter($str);
		// Outputs: A &#039;quote&#039; is &lt;b&gt;bold&lt;/b&gt;
//		echo htmlentities($str, ENT_QUOTES);


		// Script View
		print_r($v->dataToScriptView($str_1));
		
		echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
		

		print_r($v->dataToScriptEdit($ss,'editor'));

		
		
		
		
		
		exit();

    	if($this->_request->isPost())
    	{
    		$upload = new Digitalus_Resource_Image();
    		
    		$ext = Digitalus_Filesystem_File::getFileExtension($_FILES['file_field']['name']);
    		$upload->uploadImage('file_field',time().".{$ext}",'tam_tru');
    		
    		
			echo "<pre>";
			print_r($upload->fullPath);
			echo "</pre>";

			echo "<pre>";
			print_r($_FILES);
			echo "</pre>";
			exit();
    	}
    	
    	
    	
    }
    
	public function imagemanagerAction()
	{
		
	}    
    
    
    
}