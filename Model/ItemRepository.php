<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace OuterEdge\Menu\Model;

use OuterEdge\Menu\Api\Data\ItemSearchResultsInterfaceFactory;
use OuterEdge\Menu\Model\ResourceModel\Item\CollectionFactory as ItemCollectionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use OuterEdge\Menu\Api\ItemRepositoryInterface;
use OuterEdge\Menu\Model\ResourceModel\Item as ItemResource;
use OuterEdge\Menu\Api\Data\ItemInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\DB\TransactionFactory;
use Exception;

/**
 * Item repository.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ItemRepository implements ItemRepositoryInterface
{
    /**
     * @var ItemSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * Collection factory.
     *
     * @var ItemCollectionFactory
     */
    private $collectionFactory;

    /**
     * Store manager.
     *
     * @var  StoreManagerInterface
     */
    private $storeManager;

    /**
     * Scope config.
     *
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ItemResource
     */
    private $resourceModel;

    /**
     * @var ItemFactory
     */
    private $itemFactory;

    /**
     * @var JoinProcessorInterface
     */
    private $extensionAttributesJoinProcessor;

    /**
     * @var TransactionFactory
     */
    private $transactionFactory;

    /**
     * Constructs a item data object.
     *
     * @param ItemSearchResultsInterfaceFactory $searchResultsFactory
     * @param ItemCollectionFactory $collectionFactory
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param ItemResource $itemResource
     * @param ItemFactory $itemFactory
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param TransactionFactory $transactionFactory
     * @codeCoverageIgnore
     */
    public function __construct(
        ItemSearchResultsInterfaceFactory $searchResultsFactory,
        ItemCollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        ItemResource $itemResource,
        ItemFactory $itemFactory,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        TransactionFactory $transactionFactory
    ) {
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->resourceModel = $itemResource;
        $this->itemFactory = $itemFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->transactionFactory = $transactionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        /** @var ItemFactory $item */
        $item = $this->itemFactory->create();
        $this->resourceModel->load($item, $id);
        if (!$item->getId()) {
            throw new NoSuchEntityException(__('Item with specified ID "%1" not found.', $id));
        }
        return $item;
    }

    /**
     * {@inheritdoc}
     *
     * @return ItemInterface[] Array of item data objects.
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }

        $collection->addOrder('sort_order', SortOrder::SORT_ASC);

        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setTotalCount($collection->getSize());

        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());

        $searchResult->setItems($collection->getItems());
        return $searchResult;
    }

    /**
     * {@inheritdoc}
     */
    public function save(ItemInterface $item)
    {
        $id = $item->getItemId();
        if ($id) {
            $item = $this->get($id)->addData($item->getData());
        }
        try {
            $this->resourceModel->save($item);
        } catch (Exception $e) {
            throw new CouldNotSaveException(
                __('Unable to save item %1', $item->getItemId())
            );
        }
        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(ItemInterface $data)
    {
        try {
            $this->resourceModel->delete($data);
        } catch (Exception $e) {
            throw new CouldNotDeleteException(
                __('Unable to remove item %1', $data->getItemId())
            );
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($id)
    {
        $model = $this->get($id);
        $this->delete($model);
        return true;
    }

    /**
     *
     */
    public function saveItems($items)
    {
        if (!empty($items)) {
            $sortTransaction = $this->transactionFactory->create();
            foreach ($items as $itemJson) {
                $itemData = json_decode($itemJson);
                $item = $this->get($itemData->item_id)->setSortOrder($itemData->sort_order);
                $sortTransaction->addObject($item);
            }
            try {
                $sortTransaction->save();
            } catch (Exception $e) {
                return false;
            }
        }
        return true;
    }
}
