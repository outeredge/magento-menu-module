<?php

namespace OuterEdge\Menu\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;
use OuterEdge\Menu\Api\Data\ItemInterface;

/**
 * @api
 */
interface ItemSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get menu items
     *
     * @return ItemInterface[]
     */
    public function getItems();

    /**
     * Set menu items
     *
     * @param ItemInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
