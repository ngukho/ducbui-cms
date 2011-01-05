<?php

class Admin_GroupController extends Digitalus_Controller_Action
{
	private $_objGroups = null;
    /**
     * Initialize the action
     *
     * @return void
     */
    public function init()
    {
		parent::init();
		$this->_objGroups = new Model_Groups();		
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
    	
    	if(is_null($s_field))
    		$rsGroups = $this->_objGroups->fetchAll(NULL,"level DESC");
		else     		
    		$rsGroups = $this->_objGroups->fetchAllData(NULL,$s_field,$s_type);
    		
    	$this->view->rsGroups = $rsGroups;
    	
    	// Headers column
    	$this->view->nameTX = $this->view->SortColumn('Group Name','group_name',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->roleTX = $this->view->SortColumn('Role','role',$s_type,$this->_currentActionUrl,$s_field);
    	$this->view->levelTX = $this->view->SortColumn('Level','level',$s_type,$this->_currentActionUrl,$s_field);
    }
    
    public function addAction()
    {
		$val = array();   	
		
        if ($this->_request->isPost()) 
        {
			$_POST['level'] = intval($_POST['level']);
			$last_insert_id = $this->_objGroups->insertFromPost();
        	if($last_insert_id)
        	{
        		if($_POST['source_group_id'])
        			$this->_objGroups->copyPermissions($_POST['source_group_id'], $last_insert_id);
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}        	
        	$val = $this->_request->getParams();
        }
        
		$rsGroups = $this->_objGroups->fetchAll(NULL,"level DESC");
		$rowGroup = $this->_objGroups->createRow($val);
		$this->view->rowGroup = $rowGroup;
		$this->view->rsGroups = $rsGroups;
		$this->view->title_action = "Add";
		
		// Render 'group/group-form.phtml' instead of 'group/add.phtml'
		// File name must is group-form.phtml .Don't have underscore.
//		$this->_helper->viewRenderer->setRender('group-form');
		
    }
    
    public function editAction()
    {
        if ($this->_request->isPost()) 
        {
        	$_POST['level'] = intval($_POST['level']);
        	if($this->_objGroups->updateFromPost())
        	{
        		$this->_redirect($this->_currentControllerUrl);
        		return;
        	}
        	$id = $this->_request->getParam('group_id');
        	$rowGroup = $this->_objGroups->createRow($this->_request->getParams());
        }
        else 
        {
	    	$id = $this->_request->getParam('id');
	    	$rowGroup = $this->_objGroups->find($id)->current();
	    	if(!$rowGroup)
				$this->_redirect($this->_currentControllerUrl);
        }
        
        // Get current permission of group
		$currentGroup = $this->_objGroups->find($id)->current();
        $this->view->userPermissions = $currentGroup->getAclResources();        
        ///////////////////////////////////////////////////////////////
        
        $rsGroups = $this->_objGroups->fetchAll(NULL,"level DESC");
        $this->view->rsGroups = $rsGroups;
		$this->view->rowGroup = $rowGroup;
		$this->view->title_action = "Edit";
		
		// Render 'group/group-form.phtml' instead of 'group/edit.phtml'
		// File name must is group-form.phtml .Don't have underscore.
//		$this->_helper->viewRenderer->setRender('group-form');
    }    
    
    public function updatePermissionsAction()
    {
        if (Digitalus_Filter_Post::has('update_permissions')) 
        {
            //update the users permissions
            $objGroups = new Model_Groups();
            $resources = Digitalus_Filter_Post::raw('acl_resources');
            $group_id = Digitalus_Filter_Post::int('group_id');
            $rowGroup = $objGroups->find($group_id)->current();
            $rowGroup->updateAclResources($resources);
        } 
        $this->_redirect($this->_currentControllerUrl);
    }
    
    public function deleteAction()
    {
		$id = $this->_request->getParam('id');
		if(!is_null($id))
			$this->_objGroups->find($id)->current()->delete();			
		$this->_redirect($this->_currentControllerUrl);    	
    }
    
    public function deleteAllAction()
    {
		$varCheckBoxList = $this->_request->getPost('checkbox');    	
		if(!is_null($varCheckBoxList))
			foreach ($varCheckBoxList as $varID)
				$this->_objGroups->find($varID)->current()->delete();			
		$this->_redirect($this->_currentControllerUrl);
    }    


}