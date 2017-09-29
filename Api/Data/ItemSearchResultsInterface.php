<?php

namespace OuterEdge\Menu\Api\Data;

/**
 * @api
 */
interface ItemSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
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
