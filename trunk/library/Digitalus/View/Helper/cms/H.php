<?php
class Digitalus_View_Helper_Cms_H
{
//    public function H($string,$action = 'view',$style = 'input')
//    {
//    	$convert = new Digitalus_Convert();
//    	if($action == 'edit')
//    		return $convert->dataToScriptEdit($string,$style);
//    	else 
//    		return $convert->dataToScriptView($string,$style);
//    }
    
    public function h($str,$action = 'view',$style = 'input')
    {
        return nl2br(htmlspecialchars($str));
    }    
}