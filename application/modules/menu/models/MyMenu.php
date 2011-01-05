<?php

class MyMenu extends Zend_Db_Table_Abstract
{
    /**
     * table name
     *
     * @var string
     */
    protected $_name = 'access_log';


    public function log($userId)
    {
        $data['user_id'] = $userId;
        $data['uri'] = $_SERVER['REQUEST_URI'];
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        $data['date_time'] = time();

        return $data;
    }

    

}