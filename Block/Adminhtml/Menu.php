<?php

namespace OuterEdge\Menu\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Menu extends Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_menu';
        $this->_blockGroup = 'OuterEdge_Menu';
        $this->_headerText = __('Menus');
        $this->_addButtonLabel = __('Create New Menu');
        parent::_construct();
    }
}
