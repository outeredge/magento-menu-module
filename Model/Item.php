<?php

namespace OuterEdge\Menu\Model;

use OuterEdge\Menu\Api\Data\ItemInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Cms\Helper\Page;
use Magento\Framework\Registry;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;

class Item extends AbstractModel implements ItemInterface
{
    /**
     * @param Context $context
     * @param ProductFactory $productFactory
     * @param CategoryFactory $categoryFactory
     * @param Page $pageHelper
     * @param Registry $registry
     * @param AbstractResource $resource
     * @param AbstractDb $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        ProductFactory $productFactory,
        CategoryFactory $categoryFactory,
        Page $pageHelper,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->productFactory = $productFactory;
        $this->categoryFactory = $categoryFactory;
        $this->pageHelper = $pageHelper;
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    protected function _construct()
    {
        $this->_init('OuterEdge\Menu\Model\ResourceModel\Item');
    }

    /**
     * Get item ID
     *
     * @return int
     */
    public function getItemId()
    {
        return parent::getData(self::ITEM_ID);
    }

    /**
     * Get menu ID
     *
     * @return int
     */
    public function getMenuId()
    {
        return parent::getData(self::MENU_ID);
    }

    /**
     * Get parent ID
     *
     * @return int
     */
    public function getParentId()
    {
        return parent::getData(self::PARENT_ID);
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }

    /**
     * Get product ID
     *
     * @return int
     */
    public function getProductId()
    {
        return parent::getData(self::PRODUCT_ID);
    }

    /**
     * Get category ID
     *
     * @return int
     */
    public function getCategoryId()
    {
        return parent::getData(self::CATEGORY_ID);
    }
    
    /**
     * Get use subcategories
     *
     * @return int
     */
    public function getUseSubcategories()
    {
        return parent::getData(self::USE_SUBCATEGORIES);
    }

    /**
     * Get page ID
     *
     * @return int
     */
    public function getPageId()
    {
        return parent::getData(self::PAGE_ID);
    }

    /**
     * Get sort order
     *
     * @return int
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }
    
    /**
     * Get use LayoutGroup
     *
     * @return int
     */
    public function getUseLayoutGroup()
    {
        return parent::getData(self::USE_LAYOUT_GROUP);
    }  

    /**
     * Set item ID
     *
     * @param int $menuId
     * @return ItemInterface
     */
    public function setItemId($itemId)
    {
        return $this->setData(self::ITEM_ID, $itemId);
    }

    /**
     * Set menu ID
     *
     * @param int $menuId
     * @return ItemInterface
     */
    public function setMenuId($menuId)
    {
        return $this->setData(self::MENU_ID, $menuId);
    }

    /**
     * Set parent ID
     *
     * @param int $parentId
     * @return ItemInterface
     */
    public function setParentId($parentId)
    {
        return $this->setData(self::PARENT_ID, $parentId);
    }

    /**
     * Set title
     *
     * @param string $title
     * @return ItemInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ItemInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Set url
     *
     * @param string $url
     * @return ItemInterface
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * Set image
     *
     * @param string $image
     * @return ItemInterface
     */
    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * Set product ID
     *
     * @param int $productId
     * @return ItemInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Set category ID
     *
     * @param int $categoryId
     * @return ItemInterface
     */
    public function setCategoryId($categoryId)
    {
        return $this->setData(self::CATEGORY_ID, $categoryId);
    }
    
    /**
     * Set use subcategories
     *
     * @param int $useSubcategories
     * @return ItemInterface
     */
    public function setUseSubcategories($useSubcategories)
    {
        return $this->setData(self::USE_SUBCATEGORIES, $useSubcategories);
    }

    /**
     * Set page ID
     *
     * @param int $pageId
     * @return ItemInterface
     */
    public function setPageId($pageId)
    {
        return $this->setData(self::PAGE_ID, $pageId);
    }

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return ItemInterface
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::SORT_ORDER, $sortOrder);
    }
    
    /**
     * Set use layout group
     *
     * @param int $useLayoutGroup
     * @return ItemInterface
     */
    public function setUseLayoutGroup($useLayoutGroup)
    {
        return $this->setData(self::USE_LAYOUT_GROUP, $useLayoutGroup);
    }

    /**
     * Get item href link based on data
     *
     * @return string
     */
    public function getLink()
    {
        if ($this->getProductId()) {
            $product = $this->productFactory->create();
            $product->load($this->getProductId());
            return $product->getProductUrl();
        }
        if ($this->getCategoryId()) {
            $category = $this->categoryFactory->create();
            $category->load($this->getCategoryId());
            return $category->getUrl();
        }
        if ($this->getPageId()) {
            return $this->pageHelper->getPageUrl($this->getPageId());
        }
        if ($this->getUrl()) {
            return $this->getUrl();
        }
        return 'javascript:void(0);';
    }
}
