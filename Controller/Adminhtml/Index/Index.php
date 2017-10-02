<?php

namespace OuterEdge\Menu\Controller\Adminhtml\Index;

use OuterEdge\Menu\Controller\Adminhtml\Menu;
use Magento\Backend\Model\View\Result\Page;

class Index extends Menu
{
    /**
     * @return Page
     */
    public function execute()
    {
        $resultPage = $this->createActionPage();
        $resultPage->addContent(
            $resultPage->getLayout()->createBlock('OuterEdge\Menu\Block\Adminhtml\Menu')
        );
        return $resultPage;
    }
}
