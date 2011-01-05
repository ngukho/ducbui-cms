<?php
class Digitalus_View_Helper_Admin_SortColumn
{

    /**
     *
     * @return unknown
     */
    public function SortColumn($title,$sort_field,$sort_type,$base_url,$cur_sort_field = '',$filter_k = '',$function = '')
    {
    	$xhtml = null;
        $base_url .= '/s_field/'.$sort_field;
        
        if($sort_type == 1)
            $base_url .= '/s_type/0';
        else
            $base_url .= '/s_type/1';
		
		if ($filter_k!='') $base_url .= '/'.$filter_k;
      
        if($cur_sort_field == $sort_field){
            $classname = ($sort_type==1)?'sort_column_desc':'sort_column_asc';
        }else{
            $classname = 'sort_column';
        }
        $xhtml = '<nobr><a href="'.$base_url.'" class="'.$classname.'">'.$title.'</a></nobr>';
        if($function)
         	$xhtml = '<nobr><a href="javascript:'.$function.'(\''.$base_url.'\')" class="'.$classname.'">'.$title.'</a></nobr>';
        
        return $xhtml;
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
