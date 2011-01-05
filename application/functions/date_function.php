<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('now_to_mysql'))
{
    function now_to_mysql()
    {
        return date('Y-m-d H:i:s');
    }
}
if ( ! function_exists('mysql_to_fulldate'))
{
    function mysql_to_fulldate($date)
    {
        if(empty($date) || $date=='0000-00-00 00:00:00')
            return '';
        $CI = & get_instance();
        return date($CI->config->item('date_format_long'), strtotime($date));
    }
}

if ( ! function_exists('mysql_to_fulldate2'))
{
    function mysql_to_fulldate2($date)
    {
        if(empty($date) || $date=='0000-00-00 00:00')
            return '';
        $CI = & get_instance();
        return date('Y-m-d H:i', strtotime($date));
    }
}
// Convert date from mysql as yyyy-mm-dd h:i:s to date dd/mm/yy H:i
if ( ! function_exists('mysql_to_fulldate3'))
{
    function mysql_to_fulldate3($date)
    {
        if(empty($date) || $date=='0000-00-00 00:00')
            return '';
        $CI = & get_instance();
        return date('d/m/y H:i', strtotime($date));
    }
}

if ( ! function_exists('mysql_to_shortdate'))
{
    function mysql_to_shortdate($date)
    {
        if(empty($date) || $date=='0000-00-00 00:00:00')
            return '';
        $CI = & get_instance();
        return date($CI->config->item('date_format_short'), strtotime($date));
    }
}
if ( ! function_exists('mysql_to_shortdate2'))
{
    function mysql_to_shortdate2($date)
    {
        if(empty($date) || $date=='0000-00-00 00:00:00')
            return '';
        $CI = & get_instance();
        return date($CI->config->item('date_format_short2'), strtotime($date));
    }
}
if ( ! function_exists('mysql_to_shorttime'))
{
    function mysql_to_shorttime($date)
    {
        $CI = & get_instance();
		if(!strtotime($date))
		return NULL;
        return date($CI->config->item('time_format_short'), strtotime($date));
    }
}

if ( ! function_exists('mysql_to_customdate'))
{
    function mysql_to_customdate($date)
    {
        if(empty($date) || $date=='0000-00-00 00:00:00')
            return '';
        $CI = & get_instance();
        return date($CI->config->item('date_format_special'), strtotime($date));
    }
}

// check if an input date is dd/mm/yyyy
if ( ! function_exists('check_input_date'))
{
    function check_input_date($date)
    {
        $pattern = "/^(\d{1,2})[\/|\-](\d{1,2})[\/|\-](\d{4})$/";
        if(!preg_match($pattern, $date, $match))
            return FALSE;
        if(!checkdate($match[2], $match[1], $match[3]))
            return FALSE;
        return TRUE;
    }
}
// Convert input date as dd/mm/yyyy to mysql date yyyy-mm-dd
if ( ! function_exists('input_date_to_mysql'))
{
    function input_date_to_mysql($date)
    {
        if(empty($date))
            return NULL;
        $pattern = "/^(\d{1,2})[\/|\-](\d{1,2})[\/|\-](\d{4})$/";
        if(!preg_match($pattern, $date, $match))
            return '';
        if(!checkdate($match[2], $match[1], $match[3]))
            return '';
        return $match[3].'-'.$match[2].'-'.$match[1];
    }
}