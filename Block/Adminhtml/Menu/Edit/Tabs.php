<?php

namespace  OuterEdge\Menu\Block\Adminhtml\Menu\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('menu_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Menu'));
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            [
                'label' => __('Properties'),
                'title' => __('Properties'),
                'content' => $this->getChildHtml('main'),
                'active' => true
            ]
        );

        if ($this->getRequest()->getParam('menu_id')) {
            $this->addTab(
                'items',
                [
                    'label' => __('Items'),
                    'title' => __('Items'),
                    'content' => $this->getChildHtml('items')
                ]
            );
        }

        return parent::_beforeToHtml();
    }
}
