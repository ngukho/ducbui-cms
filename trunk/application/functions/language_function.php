<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2009, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Language Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/language_helper.html
 */

// ------------------------------------------------------------------------

/**
 * Lang
 *
 * Fetches a language variable and optionally outputs a form label
 *
 * @access	public
 * @param	string	the language line
 * @param	string	the id of the form element
 * @return	string
 */	
if ( ! function_exists('lang'))
{
    function lang($line, $id = '')
    {
        $CI =& get_instance();
        $line = $CI->lang->line($line);

        if ($id != '')
        {
            $line = '<label for="'.$id.'">'.$line."</label>";
        }

        return $line;
    }
}

function get_languages(){
    $CI = &get_instance();
    $CI->load->library('session');
    $CI->load->database();
    
    if($CI->session->userdata('language_data')){
        return $CI->session->userdata('language_data');
    }else{
    
        $qry = $CI->db->get('languages');
        $langs = array();
        if($qry->num_rows()>0){
            $langs = $qry->result();
        }else{
            $langs = array('language_id' => 1,
                           'language_name' => 'English',
                           'language_alias' => 'english',
                           'language_default_front_flg' => 1,
                           'language_default_admin_flg' => 1,);
        }
        $CI->session->set_userdata('language_data', $langs);
        return $langs;
    }
}

function get_current_language($section=''){
    $CI = &get_instance();
    if(empty($section)){
        $uri = $CI->uri->segment(1);
        switch(strtolower($uri)){
            case 'admin':
            case 'administrator':
                $section = 'admin';
                break;
            case 'client':
                $section = 'client';
                break;
            default:
                $section = 'front';
                break;
        }
    }
    
    $langs = get_languages();
    foreach($langs as $l){
        $k = 'language_default_'.$section.'_flg';
        if($l->$k == 1)
            return $l;
    }
}

function get_current_idiom($section=''){
    $CI = &get_instance();
    $CI->load->library('session');
    $CI->load->database();

    if(empty($section)){
        $uri = $CI->uri->segment(1);
        switch(strtolower($uri)){
            case 'admin':
            case 'administrator':
                $section = 'admin';
                break;
            case 'client':
                $section = 'client';
                break;
            default:
                $section = 'front';
                break;
        }
    }
    $key = $section.'_language';

    if($CI->session->userdata('parameter_data'))
        $params = $CI->session->userdata('parameter_data');
    else{
        $qry = $CI->db->get(PARAMETER_TABLE);
        if($qry->num_rows()>0){
            $tmps = $qry->result();
            $params = array();
            foreach($tmps as $t){
                $CI->db->where('parameter_id', $t->parameter_id);
                $qry = $CI->db->get('parameters_languages');
                $param_languages = $qry->result();
                
                foreach($param_languages as $pl){
                    $params[$t->key][$pl->language_id]['name'] = $pl->parameter_name;
                    //$params[$t->key][$pl->language_id]['description'] = $pl->parameter_description;
                }
                $params[$t->key]['id'] = @$t->id;
                $params[$t->key]['parameter_id'] = $t->parameter_id;
                $params[$t->key]['key'] = $t->key;
                $params[$t->key]['type'] = $t->type;
                $params[$t->key]['value'] = $t->value;
                $params[$t->key]['active'] = $t->active;
                $params[$t->key]['option'] = $t->option;
            }
        }
        $CI->session->set_userdata('parameter_data', $params);
    }
    if(in_array($key, array_keys($params))){
        return $params[$key]['value'];
    }else{
        $deft_lang = $CI->config->item('language');
        $idiom = ($deft_lang == '') ? 'english' : $deft_lang;
        return $idiom;
    }
}

$idiom = get_current_idiom();
/*if(!defined('COLUMN_SUBFIX')){
    $lang = get_current_idiom();
    if(strtolower(trim($lang))=='english')
        $col_subfix = '';
    else
        $col_subfix = '_'.$lang;
    define('COLUMN_SUBFIX', $col_subfix);
}*/

if(!defined('CURRENT_LANGUAGE')){
    $lang = get_current_language();
    define('CURRENT_LANGUAGE', $lang->language_id);
    define('CURRENT_LANGUAGE_ALIAS', $lang->language_alias);
}
// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./system/helpers/language_helper.php */