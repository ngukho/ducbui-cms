<?php
class Digitalus_View_Helper_Cms_ShowTreeStructural
{
    public function showTreeStructural($rsObjects,$renderTempletFile,$order = '',$level =1)
    {
		foreach($rsObjects as $rowObject) :
			$this->view->level = $level;
			$this->view->rowObject = $rowObject;
			echo $this->view->render($renderTempletFile);		
			$rsObjNextLevel = $rowObject->findDependentRowset($rowObject->getTableClass(),null,$rowObject->select()->order($order));	
			if(count($rsObjNextLevel) > 0)		
				self::showTreeStructural($rsObjNextLevel,$renderTempletFile,$order,$level + 1);
		endforeach;		
    }
    
    
//	static function ShowCategoryStructural($objView,$rsMenus,$renderTempletFile,$order = '',$level =1)
//	{
//		foreach($rsCategories as $rowCategory) :
//			$objView->categoryLevel = $level;
//			$objView->category = $rowCategory;
//			echo $objView->render($renderTempletFile);		
//			$rsCategoryNextLevel = $rowCategory->findDependentRowset($rowCategory->getTableClass(),null,
//											$rowCategory->select()->order($order));	
//			if(count($rsCategoryNextLevel) > 0)		
//				self::ShowCategoryStructural($objView,$rsCategoryNextLevel,$renderTempletFile,$order,$level + 1);
//		endforeach;		
//	}    
    
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