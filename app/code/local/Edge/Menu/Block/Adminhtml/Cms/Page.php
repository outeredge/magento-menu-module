<?php

class Edge_Menu_Block_Adminhtml_Cms_Page extends Mage_Adminhtml_Block_Cms_Page
{
    protected function _prepareLayout()
    {
        $this->setChild( 'grid',
            $this->getLayout()->createBlock( $this->_blockGroup.'/' . $this->_controller . '_grid',
            $this->_controller . '.grid')->setSaveParametersInSession(true) );
        return parent::_prepareLayout();
    }
}
