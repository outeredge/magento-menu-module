<?php

namespace OuterEdge\Menu\Model\Cms;

use Magento\Cms\Model\Page as CmsPage;

class Page extends CmsPage
{
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
     * Set page ID
     *
     * @param int $id
     * @return \Magento\Cms\Api\Data\PageInterface
     */
    public function setPageId($id)
    {
        return $this->setData(self::PAGE_ID, $id);
    }
}

