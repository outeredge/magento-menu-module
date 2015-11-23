<?php

class Edge_Menu_Block_Adminhtml_Custom_Link_Button extends Mage_Adminhtml_Block_Template
    implements Varien_Data_Form_Element_Renderer_Interface
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return '<tr><td><button type="button" class="drag-create" draggable="true">&larr; Drag to Create</button></td></tr>';
    }
}