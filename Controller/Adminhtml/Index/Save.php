<?php

namespace OuterEdge\Menu\Controller\Adminhtml\Index;

use OuterEdge\Menu\Controller\Adminhtml\Menu;
use Exception;

class Save extends Menu
{
    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $model = $this->menuFactory->create();

            $id = $this->getRequest()->getParam('menu_id');
            if ($id) {
                $model->load($id);

                if (!$model->getId()) {
                    $this->messageManager->addError(__('This menu no longer exists.'));
                    return $this->returnResult('*/*/', [], ['error' => true]);
                }
            }

            $model->addData($data);

            try {
                $model->save();

                $this->messageManager->addSuccess(__('The menu has been saved.'));
                $this->_session->setMenuData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['menu_id' => $model->getId(), '_current' => true], ['error' => false]);
                }
                return $resultRedirect->setPath('*/*/edit', ['menu_id' => $model->getId(), '_current' => true], ['error' => false]);
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                $this->_session->setMenuData($data);
                return $resultRedirect->setPath('*/*/edit', ['menu_id' => $model->getId(), '_current' => true], ['error' => true]);
            }
        }

        return $resultRedirect->setPath('*/*/', [], ['error' => true]);
    }
}
