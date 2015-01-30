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
        $this->setChild('grid_after', $this->getLayout()->createBlock('core/template', 'product.grid_after', array(
            'template' => 'menu/product.phtml')));
        return Mage_Adminhtml_Block_Widget_Container::_prepareLayout();
    }

    /**
     * Render grid
     * @return string
     */
    public function getGridHtml()
    {
        return parent::getGridHtml() . $this->getChildHtml('grid_after');
    }
}