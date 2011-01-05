<?php
/**
 * TomatoCMS
 * 
 * LICENSE
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE Version 2 
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@tomatocms.com so we can send you a copy immediately.
 * 
 * @copyright	Copyright (c) 2009-2010 TIG Corporation (http://www.tig.vn)
 * @license		http://www.gnu.org/licenses/gpl-2.0.txt GNU GENERAL PUBLIC LICENSE Version 2
 * @version 	$Id: Autoloader.php 1224 2010-02-21 19:44:56Z huuphuoc $
 * @since		2.0.3
 */

/*
	Tu dong load thu vien khi su dung no bang cach tao doi tuong
*/

class Digitalus_Autoloader implements Zend_Loader_Autoloader_Interface
{
	public function autoload($class)
	{
		$file = BASE_PATH . DIRECTORY_SEPARATOR . 'library' . DIRECTORY_SEPARATOR 
				. str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
		if (file_exists($file)) {
			require_once $file;
		}
	}
}
