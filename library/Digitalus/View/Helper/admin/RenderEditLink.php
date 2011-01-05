<?php
class Digitalus_View_Helper_Admin_RenderEditLink
{

	public function renderEditLink($src)
	{
		return "<a href='{$src}'><img src='" . BASE_IMAGES . "/btnEdit.gif' alt='Edit' border=0></a>"; 
	}
}