<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Element
 *
 * Lets you determine whether an array index is set and whether it has a value.
 * If the element is empty it returns FALSE (or whatever you specify as the default value.)
 *
 * @access	public
 * @param	string
 * @param	array
 * @param	mixed
 * @return	mixed	depends on what the array contains
 */	
if (! function_exists('element'))
{
	function send_xml_response($function_name, $result, $container='', $attribute='', $value='', $callback_script='')
	{
        $debug = FALSE;
        // Prevent the browser from caching the result.
    	// Date in the past
    	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT') ;
    	// always modified
    	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT') ;
    	// HTTP/1.1
    	header('Cache-Control: no-store, no-cache, must-revalidate') ;
    	header('Cache-Control: post-check=0, pre-check=0', false) ;
    	// HTTP/1.0
    	header('Pragma: no-cache') ;

    	// Set the response format.
    	header( 'Content-Type: text/xml; charset=utf-8' ) ;
        
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>";
    	$xml .= "<response>";
    	$xml .= "<function>".$function_name."</function>";
    	$xml .= "<result>".($result?'1':'0')."</result>";
    	$xml .= "<container>".$container."</container>";
    	$xml .= "<html><![CDATA[
".$value."
]]></html>";
        $xml .= "<attribute>".$attribute."</attribute>";
        $xml .= "<callback_script>".$callback_script."</callback_script>";
        $xml .= "<debug>".($debug?'1':'0')."</debug>";
    	$xml .= "</response>";
        print $xml;
        exit;
	}	
}
?>