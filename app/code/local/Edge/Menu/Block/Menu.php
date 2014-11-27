<?php

class Edge_Menu_Block_Menu extends Mage_Core_Block_Template
{
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

        if (empty($menu->getData())){
            return;
        }

        $html = '';
        foreach ($menu as $item){
            $html.= '<li class="level' . $level . '" data-title="' . $item->getTitle() . '">';
            $html.= '<a href="' . $item->getUrl() . '">';
            if ($item->getIsHtml() && $item->getHtml()){
                $html.= $item->getHtml();
            } else {
                $html.= $item->getTitle();
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
}
