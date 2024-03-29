<?php
class Digitalus_View_Helper_Form_LinkList
{

    /**
     * comments
     */
    public function LinkList($rowset, $linkTemplate, $replacements = array())
    {
        if (count($rowset) > 0) {
            foreach ($rowset as $row) {
                $link = $linkTemplate;
                foreach ($replacements as $tag) {
                    $link = str_replace('{' . $tag . '}', $row->$tag, $link);
                }
                $links[] = $link;
            }
            if (is_array($links)) {
                return $this->view->htmlList($links, null, null, false);
            }
        } else {
            return false;
        }
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