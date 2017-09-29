<?php

namespace OuterEdge\Menu\Controller\Adminhtml\Index;

use OuterEdge\Menu\Controller\Adminhtml\Menu;
use Magento\Framework\Controller\ResultInterface;

class Edit extends Menu
{
    /**
     * @return ResultInterface
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $model = $this->menuFactory->create();

        $id = $this->getRequest()->getParam('menu_id');
        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                $this->messageManager->addError(__('This menu no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_session->getMenuData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        $this->_coreRegistry->register('menu', $model);

        $title = $id ? __('Edit Menu') : __('New Menu');
        $resultPage = $this->createActionPage($title);
        $resultPage->getConfig()->getTitle()->prepend($id ? $model->getName() : __('New Menu'));
        return $resultPage;
    }
}
