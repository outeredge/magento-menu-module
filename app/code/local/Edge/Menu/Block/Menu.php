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
        $parentFilter = $parent ? array('eq' => $parent) : array('null' => true);
        $menu = Mage::getModel('menu/menu')
            ->getCollection()
            ->addFieldToFilter('parent', $parentFilter)
            ->setOrder('sort', 'ASC');

        if (!$menu || (isset($menu) && empty($menu->getData()))){
            return;
        }

        $html = '';
        foreach ($menu as $item){

            $class = $this->_getItemClass($item, $level);

            $html.= '<li class="' . $class . '" data-title="' . $item->getTitle() . '">';
            $html.= '<a href="' . $item->getUrl() . '">';
            if ($item->getIsHtml() && $item->getHtml()){
                $html.= $item->getHtml();
            } else {
                $html.= '<span>' . $item->getTitle() . '</span>';
            }
            $html.= '</a>';
            $html.= $this->getMenuChildren($item->getId(), $level+1);
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
                if ($item->getEntityId() === Mage::registry('current_product')->getId()) {
                    $class.= ' active';
                }
                break;
        }

        return $class;
    }
}
