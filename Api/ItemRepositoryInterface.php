<?php

namespace OuterEdge\Menu\Api;

use OuterEdge\Menu\Api\Data\ItemInterface;
use OuterEdge\Menu\Api\Data\ItemSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Menu item CRUD interface
 *
 * @api
 */
interface ItemRepositoryInterface
{
    /**
     * Return menu item by id
     *
     * @param int $itemId
     * @return ItemInterface
     */
    public function get($itemId);

    /**
     * Get menu items
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return ItemSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Create/Update new menu items with data object values
     *
     * @param ItemInterface $item
     * @return ItemInterface
     * @throws CouldNotSaveException If there is a problem with the input
     * @throws NoSuchEntityException If a ID is sent but the entity does not exist
     */
    public function save(ItemInterface $item);

    /**
     * Delete menu item by ID.
     *
     * @param int $itemId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($itemId);

    /**
     * Update multiple items
     *
     * @param string[] items
     * @return bool true on success
     */
    public function saveItems($items);
}
