<?php

//addslashes(stripslashes($this->_data[$f]));

class Digitalus_Convert
{
	
	public function scriptToData($string,$style = 'input')
	{
		$string = trim($string);
		$objhtmlEntities 	= new Zend_Filter_HtmlEntities();
		if($style == 'editor'){
			$string = stripslashes($string);
			// Old version
			//$string = $objhtmlEntities->filter($string);
		}else{
			$string = str_replace ( array("\'","\"","'",'"'), array("&#39;","&quot;","&#39;","&quot;"), $string );			
			$string = str_replace ( '<', '&lt;', $string );
			$string = str_replace ( '>', '&gt;', $string );
			$string = str_replace ( '%', '\%', $string );
			$string = str_replace ( '_', '\_', $string );
			$string = str_replace ( '-', '\-', $string );
			$string = $objhtmlEntities->filter($string);
		}
		return $string;
	}
	
	public function dataToScriptEdit($string,$style = 'input')
	{		
		$string = trim($string);
		if($style == 'editor'){
			return $string;
			// Old version
			//$string = html_entity_decode($string);
		}else{		
			$string = html_entity_decode($string);
			$string = str_replace ( array("&#39;","&quot;","&#39;","&quot;"), array("\'","\"","'",'"'), $string );
			$string = str_replace ( '&amp;lt;', '&lt;', $string );		
			$string = str_replace ( '&amp;gt;', '&gt;', $string );
			$string = str_replace ( '&lt;', '<', $string );
	      	$string = str_replace ( '&gt;', '>', $string ); 
	      	$string = str_replace ( '\\', '', $string );
		}
		return $string;
	}
	
	public function dataToScriptView($string,$style = 'input')
	{	
		$string = trim($string);
		if($style == 'editor'){
			$string = strip_tags($string);
			// Old version
//			$string = html_entity_decode($string);			
		}else{
			$string = html_entity_decode($string);
			$string = str_replace ( array("&#39;","&quot;","&#39;","&quot;"), array("\'","\"","'",'"'), $string );
			$string = str_replace ( '&amp;', '&', $string );
	       	$string = str_replace ( '&#039;', '\'', $string );
	       	$string = str_replace ( '&quot;', '\"', $string );    	  
	      	$string = str_replace ( '\\', '', $string );
		}		
		return nl2br($string);
	}
	
	//////////////////////////////////////////
	//		CAC HAM CHUC NANG STATIS		//
	//////////////////////////////////////////
	
    /**
     * changeScriptToData()  <=> scriptToData
     *
     * @param string $string
     * @param string $style = 'input'
     * @return string
     */
	static public function changeScriptToData($string,$style = 'input')
	{
		$obj = new self;
		return $obj->scriptToData($string,$style);
	}

    /**
     * changeDataToScriptEdit()  <=> dataToScriptEdit
     *
     * @param string $string
     * @param string $style = 'input'
     * @return string
     */
	static public function changeDataToScriptEdit($string,$style = 'input')
	{
		$obj = new self;
		return $obj->dataToScriptEdit($string,$style);
	}
	
    /**
     * dataToScriptView()  <=> dataToScriptView
     *
     * @param string $string
     * @param string $style = 'input'
     * @return string
     */
	static public function changeDataToScriptView($string,$style = 'input')
	{
		$obj = new self;
		return $obj->dataToScriptView($string,$style);
	}
	
}