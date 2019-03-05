<?php

namespace OuterEdge\Menu\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use OuterEdge\Menu\Model\Menu as MenuModel;
use OuterEdge\Menu\Model\MenuFactory as MenuFactory;
use OuterEdge\Menu\Model\ResourceModel\Item\Collection as ItemCollection;
use OuterEdge\Menu\Model\ResourceModel\Item\CollectionFactory as ItemCollectionFactory;
use OuterEdge\Menu\Model\Item;
use OuterEdge\Menu\Model\ItemFactory;
use Magento\Framework\Escaper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ResourceModel\Category\Collection as CategoryCollection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\Data\Collection;
use OuterEdge\Base\Helper\Image;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Menu extends AbstractHelper
{
    /**
     * @var MenuFactory
     */
    private $menuFactory;

    /**
     * @var ItemCollectionFactory
     */
    private $itemCollectionFactory;
    
    /**
     * @var ItemFactory
     */
    private $itemFactory;

    /**
     * @var Escaper
     */
    private $escaper;
    
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    
    /**
     * @var CategoryCollectionFactory
     */
    private $categoryCollectionFactory;
    
    /**
     * @var Image
     */
    private $imageHelper;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;
    
    /**
     * @param MenuFactory $menuFactory
     * @param ItemCollectionFactory $itemCollectionFactory
     * @param ItemFactory $itemFactory
     * @param Escaper $escaper
     * @param StoreManagerInterface $storeManager
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param Image $imageHelper
     * @param ScopeConfigInterface $scopeConfig
     * @codeCoverageIgnore
     */
    public function __construct(
        MenuFactory $menuFactory,
        ItemCollectionFactory $itemCollectionFactory,
        ItemFactory $itemFactory,
        Escaper $escaper,
        StoreManagerInterface $storeManager,
        CategoryCollectionFactory $categoryCollectionFactory,
        Image $imageHelper,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->menuFactory = $menuFactory;
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->itemFactory = $itemFactory;
        $this->escaper = $escaper;
        $this->storeManager = $storeManager;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->imageHelper = $imageHelper;
        $this->scopeConfig = $scopeConfig;
    }
    
    /**
     * Get menu html public wrapper
     *
     * @param MenuModel|int|string $menu
     * @param boolean $includeWrapper
     * @param boolean $loadByCode
     * @return string
     */
    public function getMenuHtml($menu, $includeWrapper = false, $loadByCode = false)
    {
        if ($menu instanceof MenuModel) {
            return $this->_getMenuHtml($menu, $includeWrapper);
        }

        $menuModel = $this->menuFactory->create();
        if ($loadByCode) {
            $menuModel->loadByCode($menu);
        } else {
            $menuModel->load($menu);
        }
        if (!$menuModel->getId()) {
            return;
        }

        return $this->_getMenuHtml($menuModel, $includeWrapper);
    }
    
    /**
     * Get menu html protected
     *
     * @param MenuModel $menu
     * @param boolean $includeWrapper
     * @return string
     */
    protected function _getMenuHtml($menu, $includeWrapper = false)
    {
        $items = $this->itemCollectionFactory->create()
            ->addFieldToFilter('menu_id', ['eq' => $menu->getId()])
            ->addFieldToFilter('parent_id', ['null' => true])
            ->setOrder('sort_order', 'ASC');

        $menuHtml = $this->_addSubMenu($items);

        if ($includeWrapper) {
            return '<ul>' . $menuHtml . '</ul>';
        }
        return $menuHtml;
    }
    
    /**
     * Add sub menu html to menu
     *
     * @param ItemCollection $items
     * @param int $level
     * @return string
     */
    protected function _addSubMenu($items, $level = 0)
    {
        $html = '';

        foreach ($items as $item) {
            $children = $this->_getItemChildren($item);
            
            if ($item->getCategoryId() && $item->getUseSubcategories()) {
                $this->_addSubcategoriesToChildren($children, $item->getCategoryId(), $level + 1);
            }

            $item->setLevel($level);
            $item->setChildren($children->count());

            $itemClass = $this->_getItemClasses($item, $level);
            $itemTitle = $this->escaper->escapeHtml($item->getTitle());

            if ($level == 2 && $item->getSortOrder() === '0') {
                $html .= '<li class="' . $itemClass . '" title="' . $itemTitle . '" data-menu-position="first">';
            } else {
                $html .= '<li class="' . $itemClass . '" title="' . $itemTitle . '">';
            }

            $html .= '<a class="' . ($item->getLevel() === 0 ? 'level-top' : '') . '" href="' . $item->getLink() . '">';
            $html .= '<span class="title">' . $item->getTitle();
            if ($item->getDescription()) {
                $html .= '<span class="description">' . $item->getDescription() . '</span>';
            }
            $html .= '</span>';
            if ($item->getImage()) {
                $itemImage = $item->getImage();
                $html .= '<span class="image"><img src="/media' . $this->imageHelper->resize(
                    $itemImage,
                    $this->scopeConfig->getValue('menu/menu_image_size/height', ScopeInterface::SCOPE_STORE), 
                    $this->scopeConfig->getValue('menu/menu_image_size/width', ScopeInterface::SCOPE_STORE))  . '" alt="' .  $item->getTitle() . '"></span>';
            }
            $html .= '</a>';
            if ($children->count()) {
                $html .= '<ul class="level' . $level . ' submenu">';
                $html .= $this->_addSubMenu($children, $level + 1);
                $html .= '</ul>';
            }
             $html .= '</li>';
        }

        $html .= '';

        return $html;
    }
    
    /**
     * Get item classes for menu item
     *
     * @param Item $item
     * @return string
     */
    protected function _getItemClasses($item)
    {
        $classes = ['level' . $item->getLevel()];
        if ($item->getLevel() === 0) {
            $classes[] = 'level-top';
        }
        if ($item->getChildren()) {
            $classes[] = 'parent';
        }
        if ($item->getImage()) {
            $classes[] = 'has-image';
        }
        return implode(' ', $classes);
    }
    
    /**
     * Get menu item children collection
     *
     * @param Item $item
     * @return ItemCollection
     */
    protected function _getItemChildren($item)
    {
        return $this->itemCollectionFactory->create()
            ->addFieldToFilter('parent_id', ['eq' => $item->getId()])
            ->setOrder('sort_order', 'ASC');
    }
    
    /**
     * Add default magento categories to children collection
     *
     * @param ItemCollection $children
     * @param int $categoryId
     * @param int $level
     * @return void
     */
    protected function _addSubcategoriesToChildren(&$children, $categoryId, $level)
    {
        $storeId = $this->storeManager->getStore()->getId();
        
        $subCategories = $this->getSubCategories($storeId, $categoryId);
        foreach ($subCategories as $subCategory) {
            $item = $this->itemFactory->create();
            $item->setData([
                'title'             => $subCategory->getName(),
                'category_id'       => $subCategory->getId(),
                'use_subcategories' => true
            ]);
            $children->addItem($item);
        }
    }
    
    /**
     * Load magento category collection from parent category
     *
     * @param int $storeId
     * @param int $categoryId
     * @return CategoryCollection
     */
    protected function getSubCategories($storeId, $categoryId)
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection->setStoreId($storeId);
        $collection->addAttributeToSelect('name');
        $collection->addFieldToFilter('parent_id', $categoryId);
        $collection->addAttributeToFilter('include_in_menu', 1);
        $collection->addIsActiveFilter();
        $collection->addUrlRewriteToResult();
        $collection->addOrder('level', Collection::SORT_ORDER_ASC);
        $collection->addOrder('position', Collection::SORT_ORDER_ASC);
        $collection->addOrder('parent_id', Collection::SORT_ORDER_ASC);
        $collection->addOrder('entity_id', Collection::SORT_ORDER_ASC);
        return $collection;
    }
}

