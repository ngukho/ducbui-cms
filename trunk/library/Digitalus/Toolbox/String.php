<?php

/**
 * Digitalus CMS
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
 * @category   Digitalus CMS
 * @package   Digitalus_Core_Library
 * @copyright  Copyright (c) 2007 - 2008,  Digitalus Media USA (digitalus-media.com)
 * @license    http://digitalus-media.com/license/new-bsd     New BSD License
 * @version    $Id: String.php Tue Dec 25 21:17:02 EST 2007 21:17:02 forrest lyman $
 */

class Digitalus_Toolbox_String
{
    /**
     * returns a randomly generated string
     * commonly used for password generation
     *
     * @param int $length
     * @return string
     */
    public static function random($length = 8)
    {
        // start with a blank string
        $string = "";

        // define possible characters
        $possible = "0123456789abcdfghjkmnpqrstvwxyz";

        // set up a counter
        $i = 0;

        // add random characters to $string until $length is reached
        while ($i < $length) {

            // pick a random character from the possible ones
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

            // we don't want this character if it's already in the string
            if (!strstr($string, $char)) {
                $string .= $char;
                $i++;
            }

        }

        // done!
        return $string;
    }

    /**
     * replaces spaces with hyphens (used for urls)
     *
     * @param string $string
     * @return string
     */
    public static function addHyphens($string)
    {
        return str_replace(' ', '-', trim($string));
    }

    /**
     * replaces hypens with spaces
     *
     * @param string $string
     * @return string
     */
    public static function stripHyphens($string)
    {
        return str_replace('-', ' ', trim($string));
    }

    /**
     * replace slashes with underscores
     *
     * @param string $string
     * @return string
     */
    public static function addUnderscores($string, $relative = false)
    {
        $string = str_replace("_", "[UNDERSCORE]", $string);
        return str_replace('/', '_', trim($string));
    }

    /**
     * replaces underscores with slashes
     * if relative is true then return the path as relative
     *
     * @param string $string
     * @param bool $relative
     * @return string
     */
    public static function stripUnderscores($string, $relative = false)
    {
        $string = str_replace('_', '/', trim($string));
        if ($relative) {
            $string = Digitalus_Toolbox_String::stripLeading('/', $string);
        }
        $string = str_replace("[UNDERSCORE]", "_", $string);
        return $string;
    }

    /**
     * strips the leading $replace from the $string
     *
     * @param string $replace
     * @param string $string
     * @return string
     */
    public static function stripLeading($replace, $string)
    {
        if (substr($string, 0, strlen($replace)) == $replace) {
            return substr($string, strlen($replace));
        } else {
            return $string;
        }
    }

    /**
     * returns the parent from the passed path
     *
     * @param string $path
     * @return string
     */
    public static function getParentFromPath($path)
    {
        $path = Digitalus_Toolbox_Regex::stripTrailingSlash($path);
        $parts = explode('/', $path);
        array_pop($parts);
        return implode('/', $parts);
    }

    /**
     * returns the current file from the path
     * this is a custom version of basename
     *
     * @param string $path
     * @return string
     */
    public static function getSelfFromPath($path)
    {
        $path = Digitalus_Toolbox_Regex::stripTrailingSlash($path);
        $parts = explode('/', $path);
        return array_pop($parts);
    }
    
    public static function truncateText($text, $count = 25, $stripTags = true)
    {
        if ($stripTags) {
            $filter = new Zend_Filter_StripTags();
            $text   = $filter->filter($text);
        }
        $words = split(' ', $text);
        $text  = (string)join(' ', array_slice($words, 0, $count));
        return $text;
    }
    
    public static function removeAccent($str) {
        if(!$str) return false;
        
        $unicode = array(
        	'a' => "a|à|á|ả|ạ|ã|ă|ằ|ắ|ẳ|ặ|ẵ|â|ầ|ấ|ẩ|ậ|ẫ",
        	'd' => "đ",
        	'e' => "e|è|é|ẻ|ẹ|ẽ|ê|ề|ế|ể|ệ|ễ",
        	'i' => "i|ì|í|ỉ|ị|ĩ",
        	'o' => "o|ò|ó|ỏ|ọ|õ|ô|ồ|ố|ổ|ộ|ỗ|ơ|ờ|ớ|ở|ợ|ỡ",
        	'u' => "u|ù|ú|ủ|ụ|ũ|ư|ừ|ứ|ử|ự|ữ",
        	'y' => "y|ỳ|ý|ỷ|ỵ",
        	'A' => "A|À|Á|Ả|Ạ|Ã|Ă|Ằ|Ắ|Ẳ|Ặ|Ẵ|Â|Ầ|Ấ|Ẩ|Ậ|Ẫ",
        	'D' => "Đ",
        	'E' => "E|È|É|Ẻ|Ẹ|Ẽ|Ê|Ề|Ế|Ể|Ệ|Ễ",
        	'I' => "I|Ì|Í|Ỉ|Ị|Ĩ",
        	'O' => "O|Ò|Ó|Ỏ|Ọ|Õ|Ô|Ồ|Ố|Ổ|Ộ|Ỗ|Ơ|Ờ|Ớ|Ở|Ợ|Ỡ",
        	'U' => "U|Ù|Ú|Ủ|Ụ|Ũ|Ư|Ừ|Ứ|Ử|Ự|Ữ",
        	'Y' => "ỵ|ỹ|Y|Ỳ|Ý|Ỷ|Ỵ|Ỹ"
        );
        
        foreach($unicode as $nonUnicode=>$uni)
        	$str = preg_replace("/($uni)/i",$nonUnicode,$str);

        return $str;
    }	
    
	public static function fixStringHtml($string)
	{
	//	$string = str_replace ( chr(147), '&quot;', $string );
	//	$string = str_replace ( chr(148), '&quot;', $string );
	//	$string = str_replace ( chr(147), '\'', $string );
	//	$string = str_replace ( chr(148), '\'', $string );
		$string = str_replace ( chr(13), '', $string );	
	//	$string = str_replace ( '\'', '&#039;', $string );
	//	$string = str_replace ( '<', '&lt;', $string );
	//	$string = str_replace ( '>', '&gt;', $string );   
	//	$string = str_replace ( '\\', '', $string );     
		$string = str_replace ( '&nbsp', ' ', $string );     
	//	$string = str_replace ( '"', '\'', $string );
		$string = str_replace ( 'â€œ', '', $string );
		$string = str_replace ( 'â€�', '', $string );
		$string = str_replace ( 'â€¦', '', $string );
	//	$string = str_replace ( 'Â�', '', $string );
		$string = str_replace ( chr(194), '.', $string );
		return $string;
	}	
}