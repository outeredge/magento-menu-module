<?php

namespace OuterEdge\Menu\Block\Adminhtml\Menu;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Helper\Data as BackendHelper;
use OuterEdge\Menu\Model\MenuFactory;
use Magento\Framework\DataObject;

class Grid extends Extended
{
    /**
     * @var MenuFactory
     */
    private $menuFactory;

    /**
     * @param Context $context
     * @param BackendHelper $backendHelper
     * @param MenuFactory $menuFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        BackendHelper $backendHelper,
        MenuFactory $menuFactory,
        array $data = []
    ) {
        $this->menuFactory = $menuFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('menuGrid');
        $this->setDefaultSort('title');
        $this->setDefaultDir('ASC');
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
        $collection = $this->menuFactory->create()->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'name',
            [
                'header' => __('Name'),
                'index'  => 'name'
            ]
        );

        $this->addColumn(
            'code',
            [
                'header' => __('Code'),
                'index'  => 'code'
            ]
        );

        $this->addColumn(
            'sort_order',
            [
                'header' => __('Sort Order'),
                'index'  => 'sort_order'
            ]
        );

        $this->_eventManager->dispatch('menu_grid_build', ['grid' => $this]);

        return parent::_prepareColumns();
    }

    /**
     * Return url of given row
     *
     * @param DataObject $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', ['menu_id' => $row->getId()]);
    }
}
