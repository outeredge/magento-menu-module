<?php

class Edge_Menu_Model_Menu extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('menu/menu');
    }

    public function getUrl()
    {
        switch ($this->getType()) {
            case "category":
                $requestPath = Mage::getSingleton('core/url_rewrite')->getResource()->getRequestPathByIdPath(
                    'category/' . $this->getEntityId(), Mage::app()->getStore()->getId());
                if (!empty($requestPath)) {
                    return Mage::getBaseUrl() . $requestPath;
                }
                return Mage::getModel('catalog/category')->load($this->getEntityId())->getUrl();
            case "product":
                $url = Mage::getResourceModel('catalog/product')->getAttributeRawValue($this->getEntityId(), 'url_key', Mage::app()->getStore()->getId());
                if ($url) {
                    return Mage::getUrl($url);
                }
                return Mage::getModel('catalog/product')->load($this->getEntityId())->getProductUrl();
            case "cms":
                return Mage::helper('cms/page')->getPageUrl($this->getEntityId());
            default:
                return $this->getData('url');
        }
    }
}
