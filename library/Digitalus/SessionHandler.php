<?php

class Digitalus_SessionHandler implements Zend_Session_SaveHandler_Interface 
{
	private static $_instance;
	
	private $_lifetime;
	
	private $_objSession;
	
	private function __construct()
	{
        // Add config to the registry so it is available sitewide
        $registry = Zend_Registry::getInstance();
        $config = $registry->get('config');
        
		$this->_lifetime = (isset($config->sessionLifetime))
							? $config->sessionLifetime
							: (int) ini_get('session.gc_maxlifetime');
		$this->_objSession = new Model_Session();
		
	}
	
	/**
	 * @return Tomato_Modules_Core_Services_SessionHandler
	 */
	public static function getInstance() 
	{
		if (null == self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function close() 
	{
		return true;
	}
	
	public function destroy($id) 
	{
		$this->_objSession->delete("session_id = '{$id}'");
//		$conn = $this->_objSession->getAdapter();
//		$where[] = 'session_id = ' . $conn->quote($id);
//		return $conn->delete(Tomato_Core_Db_Connection::getDbPrefix().'core_session', $where);
	}
	
	public function gc($maxlifetime) 
	{
		$this->_objSession->delete('modified + lifetime < ' . time());
//		$conn = $this->_objSession->getAdapter();
//		$where[] = 'modified + lifetime < ' . $conn->quote(time());
//		$conn->delete('core_session', $where);
		return true;
	}
	
	public function open($save_path, $name) 
	{
		return true;	
	}
	
	public function read($id) 
	{
		$return = '';
//		$conn = $this->_objSession->getAdapter();
//		$select = $conn->select()
//						->from(array('s' => 'core_session'))
//						->where('s.session_id = ?', $id)
//						->limit(1);
//		$row = $select->query()->fetch();
		$row = $this->_objSession->fetchRow("session_id = '{$id}'");
		if ($row != null) {
			$expirationTime = (int) $row->modified + $row->lifetime;
			if ($expirationTime > time()) {
				$return = $row->data;
			} else {
				$this->destroy($id);
			}
		}
		return $return;
	}
	
	public function write($id, $data) 
	{
//		$conn = $this->_objSession->getAdapter();
//		$row = $conn->select()
//					->from(array('s' => 'core_session'))
//					->where('s.session_id = ?', $id)
//					->limit(1)
//					->query()
//					->fetch();
		$row = $this->_objSession->fetchRow("session_id = '{$id}'");
		if (null == $row) {
			return $this->_objSession->insert(array(
				'session_id' => $id,
				'data' => $data,
				'modified' => time(),
				'lifetime' => $this->_lifetime
			));
//			return $conn->insert('core_session', array(
//				'session_id' => $id,
//				'data' => $data,
//				'modified' => time(),
//				'lifetime' => $this->_lifetime,
//			));
		} else {
//			$where = array('session_id = '.$conn->quote($id));
			return $this->_objSession->update(array(
				'data' => $data,
				'modified' => time(),
				'lifetime' => $row->lifetime,
			), "session_id = '{$id}'");
//			$where = array('session_id = '.$conn->quote($id));
//			return $conn->update('core_session', array(
//				'data' => $data,
//				'modified' => time(),
//				'lifetime' => $row->lifetime,
//			), $where);
		}
	}
}
