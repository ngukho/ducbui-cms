<?php
class Digitalus_View_Helper_General_ExplodeUrl
{
    /**
     * explode url
     * return :
     * 	Array
		(
			[url_key] => admin_menu_index
    		[module] => admin
    		[controller] => menu
    		[action] => index
    		[params] => Array
       					(
            				[key_1] => value_1
            				[key_2] => value_2
            				[key_3] => value_3
        				)
		)

     * 
     */
    public function ExplodeUrl($url)
    {
    	$result = array();
    	$url = Digitalus_Toolbox_String::stripLeading($this->view->getBaseUrl().'/',$url);
    	$array_url = explode('/',$url);
    	
    	$result['url_key'] = '';
    	$result['base_url'] = $this->view->getBaseUrl();
    	$result['module'] = array_shift($array_url);
    	$result['controller'] = array_shift($array_url);
    	$result['action'] = array_shift($array_url);
    	$result['url_key'] = $result['module'] . '_' . $result['controller'] . '_' . $result['action'];
    	
    	$params = array();
    	while($key = array_shift($array_url))
    		$params[$key] = array_shift($array_url);
    	
    	$result['params'] = $params;
    	
        return $result;
    }

    /**
     * Set this->view object
     *
     * @param  Zend_View_Interface $view
     * @return Zend_View_Helper_DeclareVars
     */
    public function setView(Zend_View_Interface $view)
    {
        $this->view = $view;
        return $this;
    }

}