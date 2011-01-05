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
 * @version    $Id: Table.php Tue Dec 25 20:37:43 EST 2007 20:37:43 forrest lyman $
 */

class Digitalus_Db_Table extends Zend_Db_Table_Abstract
{
	protected $_primaryKey;
    protected $_data;
    protected $_cache;
    protected $_errors;
    protected $_message;
    private   $_action;
    
	/**
	 * @var Digitalus_Db_Cache
	 */    
    public $cache;
    
	public function __construct($config=array()) 
	{
		parent::__construct($config);
		$this->_cache = Digitalus_Cache_Manager::getInstance();    			
		/**
		 * @var Digitalus_Db_Cache
		 */
		$this->cache = new Digitalus_Db_Cache($this);	
	}    

    public function insertFromPost()
    {
        $this->_loadPost();
        //try to run the before method
        if (method_exists($this, 'before')) {
            $this->before();
        }
        if (method_exists($this, 'beforeInsert')) {
            $this->beforeInsert();
        }


        $this->validateData();
        
        if (!$this->_errors->hasErrors()) { //there were no errors validating the data
            //since this is a insert lets set the id to null
//            unset($this->_data['id']);

            unset($this->_data[$this->_primaryKey]);
            $id = $this->insert($this->_data);
            
        	$this->_message = Digitalus_View_Message::getInstance();
        	$this->_message->add('Inserted a new row into database successfully !!!');            

            //try to run the after method
            if (method_exists($this, 'after')) {
                $this->after($id);
            }
        	
//            return $this->find($id)->current(); //i like to return the whole data object
            return $id;
        }
        return false;
    }

    public function updateFromPost()
    {
        $this->_action = 'update';
        $this->_loadPost();
        //try to run the before method
        if (method_exists($this, 'before')) {
            $this->before();
        }
        if (method_exists($this, 'beforeUpdate')) {
            $this->beforeUpdate();
        }
        $this->validateData();
//        $id = $this->_data['id'];
//        unset($this->_data['id']);

        if (!$this->_errors->hasErrors()) { //there were no errors validating the data
       		$id = $this->_data[$this->_primaryKey];
        	unset($this->_data[$this->_primaryKey]);                	
        	
//            $this->update($this->_data, 'id=' . $id);
			$number_rows_updated = $this->update($this->_data, $this->_primaryKey . '=' . $id);
			
        	$this->_message = Digitalus_View_Message::getInstance();
        	$this->_message->add('Updated current row database successfully !!!');

            //try to run the after method
            if (method_exists($this, 'after')) {
                $this->after($id);
            }
        	//return $this->find($id)->current(); //i like to return the whole data object   
        	return $number_rows_updated;         
        }
		return false;
    }

    public function switchStatus($id, $field = 'active')
    {
		$currentRow = $this->find($id)->current();
		// Kiem tra neu id ko ton tai
		if($currentRow != null)
		{ 
			$currentRow->$field = ($currentRow->$field == 0) ?  1 : 0 ;
			$currentRow->save();
			return $currentRow->$field;
//			return true;
		}
		return false;
    }
    
    // $data = Array('id' => 'value','id' => 'value')
    public function saveOrder($data, $field = 'order')
    {
    	foreach ($data as $key => $value)
    	{
    		$currentRow = $this->find($key)->current();
    		if($currentRow != null)
    		{
				$currentRow->$field = $value;
				$currentRow->save();    			
    		}
    	}
    }
    
    /*
    	$order_type = 0 => 'DESC'
    	$order_type = 1 => 'ASC'
    */
    // Tim va truy van du lieu theo cach thong thuong
    public function fetchAllData($conditions = NULL,$order_by = NULL,$order_type = 0,$limit = NULL,$offset = NULL)
    {
    	if($order_by != NULL && in_array($order_by,$this->_getCols()))
    		$order_by .= $this->sortType($order_type);
		else 
			$order_by = NULL;    		
		return $this->fetchAll($conditions,$order_by,$limit,$offset);
    }
    
    // Tim va truy van du lieu tu cache
    public function fetchAllDataFromCache($key,$conditions = NULL,$order_by = NULL,$order_type = 0,$limit = NULL,$offset = NULL)
    {
    	if($order_by != NULL && in_array($order_by,$this->_getCols()))
    		$order_by .= $this->sortType($order_type);
		else 
			$order_by = NULL;    		
		return $this->fetchAllFormCache($key,$conditions,$order_by,$limit,$offset);
    }    
    
    public function createQuery($conditions = NULL,$order_by = NULL,$order_type = 0)
    {
    	$selectCmd = $this->select();
    	if(!is_null($conditions))
    		$selectCmd->where($conditions);

    	if($order_by != NULL && in_array($order_by,$this->_getCols()))
    		$order_by .= $this->sortType($order_type);
    		
    	if(!is_null($order_by))
    		$selectCmd->order($order_by);
		return $selectCmd;
    }

    private function sortType($idx = 0)
    {
        return $idx == 1? ' DESC ' : ' ASC ';
    }
    
    /**
     * this method assumes you have registered the post data
     * it loads each of the fields from the current table and sets
     * the data hash with the unvalidated data
     *
     */
    private function _loadPost()
    {
    	if(isset($_POST['active']))
    		$_POST['active'] = intval($_POST['active']);
		if(isset($_POST['order']))
    		$_POST['order'] = intval($_POST['order']);    		
        foreach ($this->_getCols() as $col) {
            if (Digitalus_Filter_Post::has($col)) {
                $this->_data[$col] = Digitalus_Filter_Post::raw($col);
            }
        }
    }
    
    public function validateExtData($data)
    {
    	$this->_data = $data;
    	$this->validateData();
    	return !($this->_errors->hasErrors());
    }

    /**
     * this method takes the rawData hash and validates it according to the
     * rules you set in the model. this is all very simplistic by design.
     *
     * set the validation rules as parameters of the model
     *
     * $required = required fields
     *
     * $text = strip tags
     *
     * $rawText = does not strip tags
     *
     * $number = numeric
     *
     * $email = valid email
     *
     * $password = takes three parameters, the password, length, and password confirm.  if confirm
     * is set then it validates that the two are equal
     *
     * $date = converts the date to a timestamp
     *
     *
     */
    public function validateData()
    {
//        $this->_errors = new Digitalus_View_Error();
		$this->_errors = Digitalus_View_Error::getInstance();
        $validations = array('Required', 'Text', 'Integer', 'Number', 'Email', 'Password', 'Date', 'HTML', 'Unique');
        foreach ($validations as $v) {
            $validateFunction = '_validate' . $v;
            $this->$validateFunction();
        }
    }

    /**
     * sets the key's value to now (uses the timestamp)
     *
     * @param string $key
     */
    public function equalsNow($key)
    {
//        $date = new Zend_Date();
//        $this->_data[$key] = $date->get();
        $this->_data[$key] = time();
    }

    /**
     * sets the selected key to the value
     *
     * @param string $key
     * @param mixed $value
     */
    public function equalsValue($key, $value)
    {
       $this->_data[$key] = $value;
    }

    /**
     * gets the value of the key
     */
    public function getValue($key)
    {
        return $this->_data[$key];
    }

    /**
     * validates that each key in the required array exists
     * protected $_required = array('field_1','field_2');
     */
    private function _validateRequired()
    {
        if (isset($this->_required)) {
            foreach ($this->_required as $r) 
            {
            	if(!isset($this->_data[$r])) continue;
                if ($this->_data[$r] == '') {
                    $this->_errors->add('The ' . $this->_getNiceName($r) . ' is required.');
                }
            }
        }
    }

    /**
     * 
     * protected $_HTML = array('field_1','field_2');
     */    
    private function _validateHTML()
    {
    	$convert = new Digitalus_Convert();
        if (isset($this->_HTML)) {
            foreach ($this->_HTML as $f) {
                //you must strip slashes first, as the HTML editors add them
                //by doing this you are able to process both raw HTML and WYSIWYG HTML
                if (isset($this->_data[$f])) {
//                    $this->_data[$f] = addslashes(stripslashes($this->_data[$f]));
                    $this->_data[$f] = $convert->scriptToData($this->_data[$f],'editor');                    
                }
            }
        }
    }

    /**
     * 
     * protected $_unique = array('field_1','field_2');
     */        
    private function _validateUnique()
    {
        if (isset($this->_unique)) {
            //first get the original data if this is an update
            if ($this->_action == 'update') {
                $curr = $this->find($this->_data[$this->_primaryKey])->current();
            }

            foreach ($this->_unique as $f) {
            	// Neu field nay khong ton tai thi bo qua
            	if(!isset($this->_data[$f])) continue;
                //if this is an update then confirm that the field has changed
                if (($this->_action == 'update' && $curr->$f != $this->_data[$f]) || $this->_action != 'update') {
                    //note that this method is the last to run, so the data is already validated as secure
                    $rows = $this->fetchAll($f . " LIKE '{$this->_data[$f]}'");
                    if ($rows->count() > 0) {
                        $this->_errors->add('The ' . $this->_getNiceName($f) . ' ' . $this->_data[$f] . ' already exists.');
                    }
                }
            }
        }
    }

    /**
     * strips the tags from each key in the text array
     * protected $_text = array('text_1','text_2');
     */
    private function _validateText()
    {
//        $filter = new Zend_Filter_StripTags();
        $convert = new Digitalus_Convert();
        if (isset($this->_text)) {
            foreach ($this->_text as $t) {
                if (isset($this->_data[$t])) {
//                    $this->_data[$t] = $filter->filter($this->_data[$t]);
                    $this->_data[$t] = $convert->scriptToData($this->_data[$t]);
                }
            }
        }
    }

    /**
     * throws an error if any of the fields are not valid numbers
     * Ex : protected $_number = array('number_1','number_2');
     */
    private function _validateNumber()
    {
        if (isset($this->_number)) {
            $validator = new Zend_Validate_Float();
            foreach ($this->_number as $n) {
            	if(!isset($this->_data[$n])) continue;
                if (!$validator->isValid($this->_data[$n])) {
                    $this->_errors->add('The ' . $this->_getNiceName($n) . ' must be a valid number.');
                }
            }
        }
    }

	/**
     * throws an error if the fields are not valid integer numbers
     * Ex : protected $_integer = array('integer_1','integer_2');
     */    
    private function _validateInteger()
    {
        if (isset($this->_integer)) {
            foreach ($this->_integer as $n) {
            	if(!isset($this->_data[$n])) continue;
                if (!is_integer($this->_data[$n])) {
                    $this->_errors->add('The ' . $this->_getNiceName($n) . ' must be a valid integer.');
                }
            }
        }
    }

    /**
     * throws an error if the email fields are not valid email addresses
     * Ex : protected $_email = array('primary_email','secondary_email');
     */
    private function _validateEmail()
    {
        if (isset($this->_email)) {
            $validator = new Zend_Validate_EmailAddress();
            foreach ($this->_email as $e) {
            	if(!isset($this->_data[$e])) continue;
                if (!$validator->isValid($this->_data[$e])) {
                    $this->_errors->add('The ' . $this->_getNiceName($e) . ' must be a valid email address.');
                }
            }
        }
    }

    /**
     * throws and error if the password is less than the set length
     * also throws an error if the password does not match the confirmation
     * finishes up by encrypting the password
     * Ex : protected $_password = array('password',8,'confirmation_password');
     */
    private function _validatePassword()
    {
        if (isset($this->_password)) 
        {
        	// Neu khong ton tai field password thi bo qua . Dung khi update data
			if(!isset($this->_data[$this->_password[0]])) return;
            if (strlen($this->_data[$this->_password[0]]) < $this->_password[1]) 
            {
                $this->_errors->add('Your password must be at least ' . $this->_password[1] . ' characters in length.');
            }

            if (isset($_POST[$this->_password[2]])) 
            {
                if ($this->_data[$this->_password[0]] != $_POST[$this->_password[2]]) 
                {
                    $this->_errors->add('Your passwords do not match.');
                }
            }

//            $data[$this->_password[0]] = libEncrypt::encryptData($data[$this->_password[0]]);
            $this->_data[$this->_password[0]] = md5($this->_data[$this->_password[0]]);
        }
    }

    /**
     * converts all date fields to timestamps
     * Ex : protected $_date = array('start_date','end_date');
     */
    private function _validateDate()
    {
        if (isset($this->_date)) {
            foreach ($this->_date as $d) {
                if ($this->_data[$d] != '') {
                    $date = new Zend_Date($this->_data[$d]);
                    $this->_data[$d] = $date->get(Zend_Date::TIMESTAMP);
                }
            }
        }
    }

    /**
     * returns a human friendly version of the field name
     *
     * @param string $field
     * @return string
     */
    private function _getNiceName($field)
    {
        return str_replace('_', ' ',$field);
    }
    
    public function fetchAllFormCache($key, $where = null, $order = null, $count = null, $offset = null)
    {
		$cache = Digitalus_Cache_Manager::getInstance();
//		$key = $this->createCacheKey($where,$order,$count,$offset);
    	$key = $this->_name . '_' . $key;
    	
		if( ($results = $cache->loadCache($key)) != false) {
			return $results;
		}
		
		$results = $this->fetchAll($where,$order,$count,$offset);
		
        $cache->saveCache($results,$key,array($this->_name));
        return $results;    	
    }
    
    public function fetchRowFormCache($key, $where = null, $order = null)
    {
		$cache = Digitalus_Cache_Manager::getInstance();
//		$key = $this->createCacheKey($where,$order);
		$key = $this->_name . '_' . $key;
    	
		if( ($results = $cache->loadCache($key)) != false) {
			return $results;
		}
		
		$results = $this->fetchRow($where,$order);
		
        $cache->saveCache($results,$key,array($this->_name));
        return $results;
    }
    
    public function findFormCache($key,$id)
    {
		$cache = Digitalus_Cache_Manager::getInstance();
		$key = $this->_name . '_' . $key;
    	
		if( ($results = $cache->loadCache($key)) != false) {
			return $results;
		}
		
		$results = $this->find($id);
		
        $cache->saveCache($results,$key,array($this->_name));
        return $results;    	    	    	
    }
    
    public function removeDataFormCache($key)
    {
    	$cache = Digitalus_Cache_Manager::getInstance();
    	if($key == $this->_name)
    		$cache->removeCache(array($key));
    	else 
    		$cache->removeCache($key);
    }
    
    public function removeAllDataFormCache()
    {
    	$this->removeDataFormCache($this->_name);
    }    
    
//    private function createCacheKey($where = null, $order = null, $count = null, $offset = null)
//    {
//    	$key = $this->_modelName;
//    	if($where != null)
//    	{
//	    	if (!($where instanceof Zend_Db_Table_Select)) 
//	            $key .= $where->__toString();
//	    	else 
//	    		$key .= $where;
//    	}	
//    	if($order != null)
//    	{
//			if(is_array($order)) 
//				$key .= implode('_', $order);
//	        else 
//    			$key .= $order;
//
//    	}
//    	
//    	$key .= $count . $offset;	
//    	
//    	// Xoa khoang trang trong $key
//    	return str_replace(' ','',$key);
//    }

}
