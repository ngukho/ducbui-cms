<?php
/**
 * Bootstrap of Digitalus CMS
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://digitalus-media.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@digitalus-media.com so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id:$
 * @link       http://www.digitaluscms.com
 * @since      Release 1.8.0
 */

/**
 * Bootstrap of Digitalus CMS
 *
 * @copyright  Copyright (c) 2007 - 2009,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    Release: @package_version@
 * @link       http://www.digitaluscms.com
 * @since      Release 1.8.0
 */

//http://lenss.nl/2010/02/zend-framework-bootstrapping-modules/

class Virtuemart_Bootstrap extends Zend_Application_Module_Bootstrap 
{
    protected function _initModuleDefines()
    {
		define( 'DIR_VM_CATALOG_IMAGE', 'virtuemart/categories' );
		define( 'DIR_VM_MANUFACTURER_IMAGE', 'virtuemart/manufacturers' );
		define( 'DIR_VM_PRODUCT_IMAGE', 'virtuemart/products' );
		define( 'DIR_VM_PRODUCT_TYPE_IMAGE', 'virtuemart/product_types' );
		
		define( 'VM_DEFAULT_FORMAT_LONG_DATE', 'd-m-Y h:i:s' );
		define( 'VM_DEFAULT_FORMAT_SHORT_DATE', 'd-m-Y' );
		define( 'VM_DEFAULT_WIDTH_IMAGE', 400 );
		define( 'VM_DEFAULT_WIDTH_THUMB_IMAGE', 100 );

    }    
    
}

?>