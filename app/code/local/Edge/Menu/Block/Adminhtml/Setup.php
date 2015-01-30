<?php

class Edge_Menu_Block_Adminhtml_Setup extends Mage_Adminhtml_Block_Template
{
    public function getMenu()
    {
        return Mage::getModel('menu/menu')
            ->getCollection()
            ->setOrder('parent', 'ASC')
            ->setOrder('sort', 'ASC');
    }
}