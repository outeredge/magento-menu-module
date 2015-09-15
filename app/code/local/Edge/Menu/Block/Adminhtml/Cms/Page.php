<?php

class Edge_Menu_Block_Adminhtml_Cms_Page extends Mage_Adminhtml_Block_Cms_Page
{
    protected function _prepareLayout()
    {
        $this->setChild('grid',
            $this->getLayout()->createBlock('menu/adminhtml_cms_page_grid',
            $this->_controller . '.grid')->setSaveParametersInSession(true) );
        return Mage_Adminhtml_Block_Widget_Container::_prepareLayout();
    }
}
