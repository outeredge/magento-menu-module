<?php

namespace OuterEdge\Menu\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use OuterEdge\Menu\Model\ResourceModel\Menu\CollectionFactory;

class Menu implements ArrayInterface
{
    /**
     * Returns an array of menus
     *
     * @param CollectionFactory $collectionFactory
     * @codeCoverageIgnore
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $data = [];
        $collection = $this->collectionFactory->create();
        foreach ($collection as $menu) {
            $data[] = [
                'value' => $menu->getId(),
                'label' => $menu->getName()
            ];
        }
        return $data;
    }
}
