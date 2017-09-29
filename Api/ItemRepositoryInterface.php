<?php

namespace OuterEdge\Menu\Api;

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
     * @return \OuterEdge\Menu\Api\Data\ItemInterface
     */
    public function get($itemId);

    /**
     * Get menu items
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \OuterEdge\Menu\Api\Data\ItemSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Create/Update new menu items with data object values
     *
     * @param \OuterEdge\Menu\Api\Data\ItemInterface $item
     * @return \OuterEdge\Menu\Api\Data\ItemInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException If there is a problem with the input
     * @throws \Magento\Framework\Exception\NoSuchEntityException If a ID is sent but the entity does not exist
     */
    public function save(\OuterEdge\Menu\Api\Data\ItemInterface $item);

    /**
     * Delete menu item by ID.
     *
     * @param int $itemId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
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
