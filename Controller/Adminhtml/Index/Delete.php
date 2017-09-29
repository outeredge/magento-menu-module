<?php

namespace OuterEdge\Menu\Controller\Adminhtml\Index;

use OuterEdge\Menu\Controller\Adminhtml\Menu;
use Magento\Backend\Model\View\Result\Redirect;
use Exception;

class Delete extends Menu
{
    /**
     * @return Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('menu_id');
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            $model = $this->menuFactory->create();
            $model->load($id);

            try {
                $model->delete();
                $this->messageManager->addSuccess(__('You deleted the menu.'));
                return $resultRedirect->setPath('*/*/edit', ['menu_id' => $id, '_current' => true], ['error' => false]);
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['menu_id' => $id, '_current' => true], ['error' => true]);
            }
        }

        $this->messageManager->addError(__('We can\'t find a menu to delete.'));
        return $resultRedirect->setPath('*/*/', ['_current' => true], ['error' => true]);
    }
}
