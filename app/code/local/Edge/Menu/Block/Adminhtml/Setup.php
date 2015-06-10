<?php

class Edge_Menu_Block_Adminhtml_Setup extends Mage_Adminhtml_Block_Template
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    public function getMenu()
    {
        return Mage::getModel('menu/menu')
            ->getCollection()
            ->setOrder('parent', 'ASC')
            ->setOrder('sort', 'ASC');
    }
}