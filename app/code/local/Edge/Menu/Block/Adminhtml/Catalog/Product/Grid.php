<?php

class Edge_Menu_Block_Adminhtml_Catalog_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid
{
    public function getGridUrl()
    {
        return $this->getUrl('*/*/productGrid', array('_current'=>true));
    }

//    public function getRowUrl($row)
//    {
//        return $this->getUrl('*/*/edit', array(
//            'store'=>$this->getRequest()->getParam('store'),
//            'id'=>$row->getId())
//        );
//    }
}
