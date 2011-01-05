<?php
class Model_Contents extends Digitalus_Db_Table
{
    protected $_name = 'core_contents';
   	protected $_primaryKey = 'content_id'; 
    protected $_rowClass = 'Model_Content';    
    
    protected $_required = array('title','html');
    protected $_text = array('title','title_search','des_search','key_search');
    protected $_HTML = array('html');
    
    protected $_publicPath = "/public/content/view-detail";
    
    public function __construct()
    {
        parent::__construct();
    }
    
    // Before insert , update action
    protected function before()
    {
    	$this->_data['html_ext'] = strip_tags($this->_data['html']);
    	// Process title
    	$this->_data['alias'] = str_replace(" ","-",strtolower($this->_data['title']));
    }
    
    // After insert , update action
    protected function after($id)
    {
    	$current_row = $this->find($id)->current();
    	$current_row->alias_path = $this->_publicPath . '/id/' . $id . '/' . $current_row->alias;
    	$current_row->save();
    }
    

    /**
     * returns the selected content block
     *
     * @param int $id
     * @param string $node
     * @param string $version
     */
    public function fetchContent($id, $node, $version = null)
    {
        $where[] = $this->_db->quoteInto('parent_id = ?', $this->_namespace . '_' . $id);
        $where[] = $this->_db->quoteInto('node = ?', $node);
        if ($version != null) {
            $where[] = $this->_db->quoteInto('version = ?', $version);
        } else {
            $where[] = 'version IS NULL';
        }

        $row = $this->fetchRow($where);
        if ($row && !empty($row->content)) {
            return stripslashes($row->content);
        }

        return false;

    }

    /**
     * returns the content object for the selected page
     * if nodes is set then it will only return the specified nodes
     * otherwise it returns all
     *
     * @param int $pageId
     * @param array $nodes
     * @return object
     */
    public function fetchContentObject($pageId, $nodes = null, $namespace = null, $version = null)
    {
        if (null == $namespace) {
            $namespace = $this->_namespace;
        }
        $data = new stdClass();

        $where[] = $this->_db->quoteInto('parent_id = ?', $namespace . '_' . $pageId);
        if ($version != null) {
            $where[] = $this->_db->quoteInto('version = ?', $version);
        }

        $rowset = $this->fetchAll($where);

        if ($rowset->count() > 0) {
                foreach ($rowset as $row) {
                    $node = $row->node;
                    $data->$node = stripslashes($row->content);
                }
        }
        if (is_array($nodes)) {
            $return = new stdClass();
            foreach ($nodes as $node) {
                if (!empty($data->$node)) {
                   $return->$node = $data->$node;
                } else {
                   $return->$node = null;
                }
            }
            return $return;
        } else {
            return $data;
        }
    }

    public function fetchContentArray($pageId, $nodes = null, $namespace = null, $version = null)
    {
        $dataArray = array();
        $data = $this->fetchContentObject($pageId, $nodes, $namespace, $version);
        if ($data) {
            foreach ($data as $k => $v) {
                $dataArray[$k] = $v;
            }
            return $dataArray;
        } else {
            return null;
        }
    }

    public function getVersions($parentId)
    {
        $select = $this->select();
        $select->distinct(true);
        $select->where('parent_id = ?', $parentId);
        $result = $this->fetchAll($select);
        if ($result) {
            $config = Zend_Registry::get('config');
            $siteVersions = $config->language->translations;
            $versions = array();
            foreach ($result as $row) {
                $v = $row->version;
                $versions[$v] = $siteVersions->$v;
            }
            return $versions;
        }
        return null;
    }

    /**
     * this function sets a content node
     * if the node already exists then it updates it
     * if not then it inserts it
     *
     * @param int $pageId
     * @param string $node
     * @param string $content
     * @param string $version
     */
    public function set($pageId, $node, $content, $version = null)
    {
        $node = strtolower($node);

        $where[] = $this->_db->quoteInto('parent_id = ?', $this->_namespace . '_' . $pageId);
        $where[] = $this->_db->quoteInto('node = ?', $node);
        if ($version != null) {
           $where[] = $this->_db->quoteInto('version = ?', $version);
        }

        $row = $this->fetchRow($where);


        if ($row) {
            $row->content = $content;
            $row->save();
        } else {
            $data = array(
               'parent_id' => $this->_namespace . '_' . $pageId,
               'node'      => $node,
               'content'   => $content
            );
            if ($version != null) {
                $data['version'] = $version;
            }
            $this->insert($data);
        }
    }
}

class Model_Content extends Zend_Db_Table_Row_Abstract 
{ 
    
    
} 