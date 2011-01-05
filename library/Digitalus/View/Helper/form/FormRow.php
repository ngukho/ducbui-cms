<?php
class Digitalus_View_Helper_Form_FormRow
{

    /**
     * comments
     */
    public function FormRow($label, $control, $required = false ,$label_extra = "width='25%'",$is_textarea = false)
    {
        $class = null;
        $align_top = null;
        
        
        if ($is_textarea) $align_top = ' align_top';
        if ($required) $class = "<span class='required_field'>*</span>";
        
        $xhtml = "<td class='rowBlue_02{$align_top}' {$label_extra} ><label>{$label}{$class}</label></td>". PHP_EOL;
        $xhtml .= "<td class='rowBlue_01'>{$control}</td>";
        
        return "<tr>{$xhtml}</tr>";
        
//
//				<tr>
//					<td width="25%" class="rowBlue_02">
//						<label>Name :<span class="required_field">*</span></label>					
//					</td> 
//					<td class="rowBlue_01"> 
//						<input type="text" name="name" id="name" class="required" size="35">
//					</td>
//				</tr>         
//        
        


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