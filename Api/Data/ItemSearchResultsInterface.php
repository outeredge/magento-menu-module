<?php

namespace OuterEdge\Menu\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * @api
 */
interface ItemSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get menu items
     *
     * @return \OuterEdge\Menu\Api\Data\ItemInterface[]
     */
    public function getItems();

    /**
     * Set menu items
     *
     * @param \OuterEdge\Menu\Api\Data\ItemInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
