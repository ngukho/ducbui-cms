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
	function unassoc2assoc($src_array, $key_item)
	{
        $dest_array = array();
		foreach( $src_array as $src_item){
            $dest_array[$src_item[$key_item]] = $src_item;
        }
		return $dest_array;
	}	
}
?>