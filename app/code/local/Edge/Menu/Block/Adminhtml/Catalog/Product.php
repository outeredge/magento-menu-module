<?php

class Edge_Menu_Block_Adminhtml_Catalog_Product extends Mage_Adminhtml_Block_Catalog_Product
{
    /**
     * Prepare grid
     * @return Mage_Adminhtml_Block_Catalog_Product
     */
    protected function _prepareLayout()
    {
        $this->setChild('grid', $this->getLayout()->createBlock('menu/adminhtml_catalog_product_grid', 'product.grid'));
        return Mage_Adminhtml_Block_Widget_Container::_prepareLayout();
    }
}