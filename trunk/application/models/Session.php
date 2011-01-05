<?php

class Model_Session extends Digitalus_Db_Table
{
    /**
     * the table name
     *
     * @var string
     */
    protected $_name = 'core_session';
    protected $_primaryKey = 'session_id'; 
    
    public function __construct()
    {
        parent::__construct();
    }            


}