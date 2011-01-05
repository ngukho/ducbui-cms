<?php
class Mod_Site_Menu_IndexController extends Digitalus_Controller_Action
{
    private $_objSiteMenus = null;
    
    public function init()
    {
    	parent::init();
		// Load model trong bat ky module nao
		$this->loadModel('SiteMenus');
    	$this->_objSiteMenus = new SiteMenus();
    }

    /**
     * The default action
     *
     * Display the main menu site page
     *
     * @return void
     */
    public function indexAction()
    {
    	$conditions = "parent = 0";
		$this->view->menuLevelOnes = $this->_objSiteMenus->fetchAll($conditions,"order ASC");
		$this->view->menuRoot = $this->_objSiteMenus->fetchAll("parent = 0","order ASC");
    }    
    
    public function addAction()
    {
    	$val = array('parent'=>0,'site_menu_link'=>'','menu_position_id'=>0,'order'=>0,'active'=>0);
    	if ($this->_request->isPost()) 
    	{
    		if(!isset($_POST['site_menu_img'])) $_POST['site_menu_img'] = '';
			if($this->_objSiteMenus->insertFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}        	
        	$val = $this->_request->getParams();    		
    	}
    	
        $this->view->menuLevelOnes = $this->_objSiteMenus->fetchAll("parent = 0","order ASC");
		$this->view->parentMenu = $this->loadTreeSiteMenuSelectBox('parent',null,$val['parent'],array('class'=>'required','size'=>1,'style'=>'width:300px;'));
		$this->view->menuPosition = $this->createSelectMenuPosition('menu_position_id',$val['menu_position_id'],array('style' => 'width:150px;'));
        $this->view->rowMenu = $this->_objSiteMenus->createRow();
    	$this->view->title_action = $this->view->getTranslation('Add');
    }
    
    public function editAction()
    {
        if ($this->_request->isPost()) 
        {
			if(!isset($_POST['site_menu_img'])) $_POST['site_menu_img'] = $_POST['menu_icon'];
        	if($this->_objSiteMenus->updateFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}
        	$rowParam = $this->_objSiteMenus->createRow($this->_request->getParams());
        }
        else 
        {
	    	$id = $this->_request->getParam('id');
	    	$rowMenu = $this->_objSiteMenus->find($id)->current();
	    	if(!$rowMenu)
				$this->_redirect($this->_currentControllerUrl);
        }
        
		$this->view->rowMenu = $rowMenu;
		$this->view->parentMenu = $this->view->loadTreeMenuSelectBox('parent',null,$rowMenu->parent,array('class'=>'required','size'=>1,'style'=>'width:300px;'));
		$this->view->menuPosition = $this->createSelectMenuPosition('menu_position_id',$rowMenu->menu_position_id,array('style' => 'width:150px;'));
		$this->view->title_action = $this->view->getTranslation('Edit');
    }    
    
    public function switchStatusAction()
    {
	   	$id = (int) $this->_request->getParam('id');
	   	$status = $this->_objSiteMenus->switchMenuStatus($id);
    	$this->_redirect($this->_currentControllerUrl);
    }    
    
    public function saveOrderAction()
    {
    	$this->_objSiteMenus->saveOrder($_POST['order']);
    	exit();
    }    
    
    public function deleteAction()
    {
    	$id = (int) $this->_request->getParam('id');
    	// Override phuong thuc delete trong class Menus
    	$this->_objSiteMenus->delete($id);
    	$this->_redirect($this->_currentControllerUrl);
    }
    
    public function deleteAllAction()
    {
		$varCheckBoxList = $this->_request->getPost('checkbox');    	
		if(!is_null($varCheckBoxList))
		{
			foreach ($varCheckBoxList as $varID)
			{
				$rowMenu = $this->_objSiteMenus->find($varID)->current();
				if($rowMenu != null)
					$this->_objSiteMenus->delete($varID);				
			}
		}
		$this->_redirect($this->_currentControllerUrl);
    }    
    
    private function _indexAction()
    {
    	echo "<pre>";
    	print_r('dfgdfgdgd');
    	echo "</pre>";
    	exit();
//    	Zend_Loader::loadClass();
//    	Zend_Layout_Controller_Plugin_Layout

		// Load helper trong cac module
		$this->loadHelper();

//		$this->loadModel('MyMenu','admin');

		// Load model trong bat ky module nao
		$this->loadModel('MyMenu');
//		
//		$this->loadModel('MyMenu','contact');
//		
//		$this->loadModel('MyMenu','public');
//		exit();

    	$m = new MyMenu();
    	
    	$data = $m->log(5);
    	
    	echo "<pre>";
    	print_r($this->view->menuHelper("Bui Van Tien Duc"));
    	echo "<br>";
    	
    	echo "<pre>";
    	print_r($data);
    	echo "<br>";
    	print_r($this->view->currentActionUrl);
    	echo "</pre>";
    	exit();
    	
    }
    
    private function createSelectMenuPosition($name,$selected = null,$attribsSelect = null)
    {
    	$this->loadModel('MenuPositions');
    	$objPositions = new MenuPositions();
    	$rsPositions = $objPositions->fetchAll('active = 1', "order ASC");
    	
    	$options = array();
		foreach ($rsPositions as $rsPosition)
			$options[$rsPosition->menu_position_id] = $rsPosition->name; 
			
		return $this->view->formSelect($name, $selected, $attribsSelect, $options); 		
		
    	
    }    
    
	/*
		$name => Ten cua selectbox
		$FirstItem => ID goc muon load
		$selected => Chon gia tri trong selectbox
		$attribsSelect => Thuoc tinh cua selectbox
		$subtractId => load tru id nay ra (nhanh nay cung nhanh con cua no se bi loai bo trong wa trinh load)
						dung khi tao tree select box cho update action
	*/
	private function loadTreeSiteMenuSelectBox($name,$FirstItem = null,$selected = null,$attribsSelect = null,$subtractId = null)
	{
    	$objSiteMenus = new SiteMenus();
		$FirstItem = ($FirstItem == null ? 0 : $FirstItem);
    	$menuLevelOnes = $objSiteMenus->fetchAll("parent = {$FirstItem}", "order ASC");
    	
    	$options = array();
    	if($FirstItem == 0)
			$options[0] = "/ ROOT"; 
		else 
			$options[0] = $objSiteMenus->fetchRow("menu_id = {$FirstItem}")->name; 
		
		$this->_loadMenuTree($menuLevelOnes,$options,1,$subtractId);
		
		$view = new Zend_View();
		return $view->formSelect($name, $selected, $attribsSelect, $options); 
	}
	
    private function _loadMenuTree($argMenuList,&$argArrMenu,$argLevel,$subtractId)
    {
//    	$objMenus = new Model_Menus();
		$objSiteMenus = new SiteMenus();    	
    	foreach ($argMenuList as $objMenu)
    	{
    		$len = strlen($objMenu->name);
    		$str = str_pad("|__ " . $objMenu->name,$len + ($argLevel * 6), "-" ,STR_PAD_LEFT);
    		$argArrMenu[$objMenu->menu_id] = $str;
    		if($objMenu->menu_id == $subtractId) return;
			$objMenuNextLevel = $objSiteMenus->fetchAll("parent = {$objMenu->menu_id}", "order ASC");
			$this->_loadMenuTree($objMenuNextLevel,$argArrMenu,$argLevel + 1,$subtractId);
    	}    	
    }    
    

}