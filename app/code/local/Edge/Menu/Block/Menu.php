<?php

class Edge_Menu_Block_Menu extends Mage_Core_Block_Template
{
    protected $_activeCategories = array();

    public function _construct()
    {
        parent::_construct();

        if (Mage::registry('current_category')) {
            $this->_activeCategories = Mage::registry('current_category')->getPathIds();
        }
    }

    public function getMenu()
    {
        return $this->getMenuChildren(null, 0);
    }

    public function getMenuChildren($parent, $level)
    {
        $htmlProcessor = Mage::helper('cms')->getBlockTemplateProcessor();

        $parentFilter = $parent ? array('eq' => $parent) : array('null' => true);
        $menu = Mage::getModel('menu/menu')
            ->getCollection()
            ->addFieldToFilter('parent', $parentFilter)
            ->setOrder('sort', 'ASC');

        $menuData = $menu->getData();
        if (!$menu || (isset($menu) && empty($menuData))){
            return;
        }

        $html = '';
        foreach ($menu as $item){

            $class = $this->_getItemClass($item, $level);
            $children = $this->getMenuChildren($item->getId(), $level+1);

            if ($children){
                $class.= ' parent';
            }

            $html.= '<li class="' . $class . '" data-title="' . $item->getTitle() . '">';
            $html.= '<a href="' . $item->getUrl() . '">';
            if ($item->getImage()) {
                $html.= '<img src="' . Mage::helper('edge/image')->getImage($item->getImage()) . '" alt="' . $item->getTitle() . '">';
            }
            if ($item->getIsHtml() && $item->getHtml()){
                $html.= $htmlProcessor->filter($item->getHtml());
            } else {
                $html.= '<span>' . $item->getTitle() . '</span>';
            }
            $html.= '</a>';
            if ($children){
                $html.= $children;
            }
            $html.= '</li>';
        }

        if ($parent) {
            $html = '<ul class="level' . ($level-1) . '">' . $html . '</ul>';
        }
        return $html;
    }

    protected function _getItemClass($item, $level)
    {
        $class = 'level' . $level;

        switch ($item->getType()) {
            case "category":
                if (in_array($item->getEntityId(), $this->_activeCategories)) {
                    $class.= ' active';
                }
                break;
            case "product":
                if (Mage::registry('current_product') && (Mage::registry('current_product')->getId() === $item->getEntityId())) {
                    $class.= ' active';
                }
                break;
            case "cms":
                if (Mage::getBlockSingleton('cms/page')->getPage()->getId() == $item->getEntityId()) {
                    $class.= ' active';
                }
                break;
            case "custom":
                $url = Mage::getSingleton('core/url')->parseUrl(Mage::helper('core/url')->getCurrentUrl());
                if (rtrim($url->getPath(),'/') == $item->getUrl()) {
                    $class.= ' active';
                }
                break;
        }

        return $class;
    }
}
