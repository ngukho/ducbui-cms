<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('h'))
{
    function h($str)
    {
        return nl2br(htmlspecialchars($str));
    }
}

if ( ! function_exists('n'))
{
    function n($str)
    {
        return number_format($str, 2, '.', ',');
    }
}

if ( ! function_exists('sort_column'))
{
    function sort_column($title, $sort_field, $sort_type, $base_url, $cur_sort_field=''){
        $base_url = trim($base_url, '/').'/'.$sort_field;
        
        if($sort_type == 1)
            $base_url .= '';
        else
            $base_url .= '/1';
        
        if($cur_sort_field == $sort_field){
            $classname = ($sort_type==1)?'sort_column_desc':'sort_column_asc';
        }else{
            $classname = 'sort_column';
        }
        $s = '<nobr><a href="'.site_url($base_url).'" class="'.$classname.'">'.$title.'</a></nobr>';
        return $s;
    }
}

if ( ! function_exists('use_lightbox'))
{
    function use_lightbox()
    {
        $s = '<script type="text/javascript" src="'.base_url().'application/application/assets/scripts/ibox.js"></script>
              <script type="text/javascript">
                iBox.setPath(\''.base_url().'application/assets/styles/\');
                iBox.fade_in_speed = 20;
              </script>
              <link rel="stylesheet" href="'.base_url().'application/assets/styles/lightbox.css" type="text/css" media="screen"/>';
        return $s;
    }
}

function get_current_admin_theme(){
    $CI = &get_instance();
    return $CI->config->item('admin_default_theme');
}

function get_default_front_theme(){
    $CI = &get_instance();
    
    if($CI->session->userdata('PARAM_front_default_theme')){
        return $CI->session->userdata('PARAM_front_default_theme');
    }
    
    $CI->load->module_model('administrator.parameters.parameter_model');
    $theme = $CI->parameter_model->get_by_key('front_default_theme');
    set_default_front_theme($theme);
    return $theme;
}

function set_default_front_theme($t){
    $CI = &get_instance();
    $CI->session->set_userdata('PARAM_front_default_theme', $t);
}