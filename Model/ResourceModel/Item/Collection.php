<?php

namespace OuterEdge\Menu\Model\ResourceModel\Item;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'OuterEdge\Menu\Model\Item',
            'OuterEdge\Menu\Model\ResourceModel\Item'
        );
    }
}
