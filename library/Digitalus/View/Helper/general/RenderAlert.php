<?php
class Digitalus_View_Helper_General_RenderAlert
{

    /**
     * comments
     */
    public function RenderAlert()
    {
//        $m = new Digitalus_View_Message();    	
        $m = Digitalus_View_Message::getInstance();
//        $ve = new Digitalus_View_Error();
        $ve = Digitalus_View_Error::getInstance();
        $alert = false;
        $message = null;
        $verror = null;

        $alert = null;

        if ($ve->hasErrors()) {
//            $verror = '<p>'. $this->view->getTranslation('The following errors have occurred') . ':</p>' . $this->view->HtmlList($ve->get());
//            $alert .= '<fieldset><legend>'. $this->view->getTranslation('Errors') . '</legend>' . $verror . '</fieldset>';
            $verror = "<div class='title'>Errors !!!</div>". $this->view->HtmlList($ve->get(),false,array('class'=>'box-item'));
            $alert .= "<div class='box-error'>" . $verror . '</div>';
            
        }
        

        if ($m->hasMessage()) {
//            $message .= '<p>' . $m->get() . '</p>';
//            $alert   .= '<fieldset><legend>'. $this->view->getTranslation('Message') . '</legend>' . $message . '</fieldset>';
            $alert .= "<div class='tag-info'>{$m->get()}</div>";
        }

        //after this renders it clears the errors and messages
        $m->clear();
        $ve->clear();

        return $alert;
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