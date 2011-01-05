<?php
class Digitalus_View_Helper_Admin_RenderDeleteLink
{

	/**
	 * comments
	 */
	public function renderDeleteLink($src)
	{
	    return "<a href='javascript:void(0);' onclick='deleteRow(\"{$src}\",\"Do you want to delete this row ?\")'><img src='" . BASE_IMAGES . "/btnDelete.gif' alt='Delete' border=0></a>"; 
	}
}