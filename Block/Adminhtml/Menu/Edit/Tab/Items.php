<?php

namespace OuterEdge\Menu\Block\Adminhtml\Menu\Edit\Tab;

use Magento\Backend\Block\Template;
use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;

class Items extends Template
{
    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    public function getMenuId()
    {
        return $this->_coreRegistry->registry('menu')->getId();
    }
}
