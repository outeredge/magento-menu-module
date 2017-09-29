<?php

namespace OuterEdge\Menu\Block\Adminhtml\Menu\Edit\Tab;

use Magento\Backend\Block\Template;

class Items extends Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
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
