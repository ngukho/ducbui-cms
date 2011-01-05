<?php

require_once ('Zend/Db/Table/Row/Abstract.php');

class Digitalus_Db_Table_Row extends Zend_Db_Table_Row_Abstract {
	/**
	 * @var Digitalus_Db_Cache 
	 */
	public $cache;
	
	public function __construct($config=array()) {
		parent::__construct($config);

		$this->cache = new Digitalus_Db_Cache($this);
	}
		
	public function setTable(Zend_Db_Table_Abstract $table = null)
    {
        if ($table == null) {
            $this->_table = null;
            $this->_connected = false;
            return false;
        }

        $tableClass = get_class($table);
        
        /** 
        // we lost $this->_tableClass, so this would throw always an exception
		// we have to ignore this for now.

        if (! $table instanceof $this->_tableClass) {
            require_once 'Zend/Db/Table/Row/Exception.php';
            throw new Zend_Db_Table_Row_Exception("The specified Table is of class $tableClass, expecting class to be instance of $this->_tableClass");
        }
		*/

        $this->_table = $table;
        $this->_tableClass = $tableClass;

        $info = $this->_table->info();

        if ($info['cols'] != array_keys($this->_data)) {
            require_once 'Zend/Db/Table/Row/Exception.php';
            throw new Zend_Db_Table_Row_Exception('The specified Table does not have the same columns as the Row');
        }

        if (! array_intersect((array) $this->_primary, $info['primary']) == (array) $this->_primary) {

            require_once 'Zend/Db/Table/Row/Exception.php';
            throw new Zend_Db_Table_Row_Exception("The specified Table '$tableClass' does not have the same primary key as the Row");
        }
        
        return true;
    }	
    
    /**
     * Set the table
     * this is neccessary if the row lies in cache
     * 
     * also we setting automaticly a cache suffix
     * 
     * @param $table Zend_Db_Table_Abstract A instanz of a Model.
     */
    public function prep($table){
    	$this->setTable($table);
        $table=$this->getTable()->info();
        $tablename=$table['name'];
        $primary=$table['primary'][1];

        $this->cache->setCacheSuffix($tablename.'__'.$this->$primary.'__');    	
    }
}

?>