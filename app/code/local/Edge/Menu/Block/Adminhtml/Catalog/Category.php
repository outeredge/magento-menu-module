<?php

class Edge_Menu_Block_Adminhtml_Catalog_Category extends Mage_Adminhtml_Block_Template
{
    public function getCategoryTree()
    {
        return $this->getChildCategories(null);
    }

    protected function getChildCategories($parentId)
    {
        $html = '<ul>';
        $categories = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('parent_id', array('eq' => $parentId));

        foreach ($categories as $category){
            $html .= '<li><span data-id="' .  $category->getId() . '">' . $category->getName() . '</span>';
            if($category->getChildren()){
                $html.= $this->getChildCategories($category->getId());
            }
            $html .= '</li>';
        }
        $html.= '</ul>';
        return $html;
    }
}