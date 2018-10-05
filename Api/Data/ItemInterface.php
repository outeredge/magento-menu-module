<?php

namespace OuterEdge\Menu\Api\Data;

/**
 * Interface ItemInterface
 * @api
 */
interface ItemInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ITEM_ID           = 'item_id';
    const MENU_ID           = 'menu_id';
    const PARENT_ID         = 'parent_id';
    const TITLE             = 'title';
    const DESCRIPTION       = 'description';
    const URL               = 'url';
    const IMAGE             = 'image';
    const PRODUCT_ID        = 'product_id';
    const CATEGORY_ID       = 'category_id';
    const USE_SUBCATEGORIES = 'use_subcategories';
    const PAGE_ID           = 'page_id';
    const SORT_ORDER        = 'sort_order';
    const USE_LAYOUT_GROUP  = 'use_layout_group';
    /**#@-*/

    /**
     * Get item ID
     *
     * @return int|null
     */
    public function getItemId();

    /**
     * Get menu ID
     *
     * @return int|null
     */
    public function getMenuId();

    /**
     * Get parent ID
     *
     * @return int|null
     */
    public function getParentId();

    /**
     * Get title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Get description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Get url
     *
     * @return string|null
     */
    public function getUrl();

    /**
     * Get image
     *
     * @return string|null
     */
    public function getImage();

    /**
     * Get product ID
     *
     * @return int|null
     */
    public function getProductId();

    /**
     * Get category ID
     *
     * @return int|null
     */
    public function getCategoryId();
    
    /**
     * Get use subcategories
     *
     * @return int|null
     */
    public function getUseSubcategories();

    /**
     * Get page ID
     *
     * @return int|null
     */
    public function getPageId();

    /**
     * Get sort order
     *
     * @return int|null
     */
    public function getSortOrder();

    /**
     * Get layout group
     *
     * @return int|null
     */
    public function getUseLayoutGroup();

    /**
     * Set item ID
     *
     * @param int $id
     * @return $this
     */
    public function setItemId($id);

    /**
     * Set menu ID
     *
     * @param int $menuId
     * @return $this
     */
    public function setMenuId($menuId);

    /**
     * Set parent ID
     *
     * @param int $parentId
     * @return $this
     */
    public function setParentId($parentId);

    /**
     * Set title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Set description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Set url
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url);

    /**
     * Set image
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image);

    /**
     * Set product ID
     *
     * @param int $productId
     * @return $this
     */
    public function setProductId($productId);

    /**
     * Set category ID
     *
     * @param int $categoryId
     * @return $this
     */
    public function setCategoryId($categoryId);
    
    /**
     * Set use subcategories
     *
     * @param int $useSubcategories
     * @return $this
     */
    public function setUseSubcategories($useSubcategories);

    /**
     * Set page ID
     *
     * @param int $pageId
     * @return $this
     */
    public function setPageId($pageId);

    /**
     * Set sort order
     *
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);

    /**
     * Set layout group
     *
     * @param int $useLayoutGroup
     * @return $this
     */
    public function setUseLayoutGroup($useLayoutGroup);
}
