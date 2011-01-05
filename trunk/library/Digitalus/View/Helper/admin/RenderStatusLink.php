<?php
class Digitalus_View_Helper_Admin_RenderStatusLink
{

	/**
	 * comments
	 */
	public function renderStatusLink($id,$src,$status,$ajax = true)
	{
		if($ajax)
			$xhtml = "<a href='javascript:void(0);' onclick='changeStatus(\"{$src}\",\"img_status_{$id}\")'>";
		else 
			$xhtml = "<a href='{$src}'>";					
			
		$image = BASE_IMAGES . '/btnInactive.gif';
		$alt = 'Inactive';
		if($status == 1)
		{
			$image = BASE_IMAGES . '/btnActive.gif';
			$alt = 'Active';
		}
		return $xhtml . "<img id='img_status_{$id}' width='15px' height='15px' src='{$image}' alt='{$alt}' border=0>" . "</a>";
	}
}