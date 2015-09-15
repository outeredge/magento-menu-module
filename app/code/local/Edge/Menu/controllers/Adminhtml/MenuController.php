<?php

class Edge_Menu_Adminhtml_MenuController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/menu');
    }

    public function indexAction()
    {
        if ($this->getRequest()->getParam('website', null)) {
            $this->loadLayout('adminhtml_menu_website_selected');
        } else {
            $this->loadLayout();
        }
        $this->renderLayout();
    }

    public function createAction()
    {
        $data = $this->getRequest()->getParams();

        if ($data['type'] === 'product') {
            $product = Mage::getModel('catalog/product')->load($data['id']);
            $data['title'] = $product->getName();
            $data['entity_id'] = $data['id'];
        }
        elseif ($data['type'] === 'category') {
            $category = Mage::getModel('catalog/category')->load($data['id']);
            $data['title'] = $category->getName();
            $data['entity_id'] = $data['id'];
        }
        elseif ($data['type'] === 'cms') {
            $cms = Mage::getModel('cms/page')->load($data['id']);
            $data['title'] = $cms->getTitle();
            $data['entity_id'] = $data['id'];
        }
        unset($data['id']);

        $item = Mage::getModel('menu/menu');
        $this->_setData($item, $data);

        if (isset($data['sorting']) && is_array($data['sorting'])){
            $this->_sortMenu($item, $data['sorting']);
        }

        try {
            $item->save();
        } catch (Exception $e) {
            die($e->getMessage());
            return false;
        }

        $return = $item->getData();
        if (isset($data['sorting']) && is_array($data['sorting'])){
            if (isset($data['sorting']['before'])) {
                $return['before'] = $data['sorting']['before'];
            } else {
                $return['after'] = $data['sorting']['after'];
            }
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($return));
        return true;
    }

    public function updateAction()
    {
        $data = $this->getRequest()->getParams();

        $item = Mage::getModel('menu/menu');
        $item->load($data['id']);
        $this->_setData($item, $data);

        try {
            $item->save();
        } catch (Exception $e) {
            die($e->getMessage());
            return false;
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($item->getData()));
        return true;
    }

    public function deleteAction()
    {
        return Mage::getModel('menu/menu')->load($this->getRequest()->getParam('id'))->delete();
    }

    public function parentAction()
    {
        $data = $this->getRequest()->getParams();
        $parent = null;
        if ($data['parent']) {
            $parent = $data['parent'];
        }

        $item = Mage::getModel('menu/menu');
        $item->load($data['id']);
        $item->setParent($parent);

        if (isset($data['sorting']) && is_array($data['sorting'])){
            $this->_sortMenu($item, $data['sorting']);
        }

        try {
            $item->save();
        } catch (Exception $e) {
            die($e->getMessage());
            return false;
        }

        $return = $item->getData();
        if (isset($data['sorting']) && is_array($data['sorting'])){
            if (isset($data['sorting']['before'])) {
                $return['before'] = $data['sorting']['before'];
            } else {
                $return['after'] = $data['sorting']['after'];
            }
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($return));
        return true;
    }

    protected function _setData($item, $data)
    {
        $item->setWebsiteId($data['website_id']);
        $item->setTitle($data['title']);
        $item->setClass($data['class']);

        if (isset($data['type']) && $data['type'] !== '') {
            $item->setType($data['type']);
        }

        if (isset($data['parent']) && $data['parent'] !== '') {
            $item->setParent($data['parent']);
        }

        if (isset($data['entity_id']) && $data['entity_id'] !== '') {
            $item->setEntityId($data['entity_id']);
        }

        if (isset($data['url']) && $data['url'] !== '') {
            $item->setUrl($data['url']);
        }

        if (isset($data['is_html']) && $data['is_html'] === 'on'){
            $item->setIsHtml('1');
            $item->setHtml($data['html']);
        } else {
            $item->setIsHtml('0');
        }

        if (!empty($_FILES)) {
            foreach ($_FILES as $name=>$fileData) {
                if (isset($fileData['name']) && $fileData['name'] !== '') {
                    try {
                        $uploader = new Mage_Core_Model_File_Uploader($name);
                        $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png','svg'));
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(false);

                        $dirPath = Mage::getBaseDir('media') . DS . 'menu' . DS;
                        $result = $uploader->save($dirPath, $fileData['name']);
                    } catch (Exception $e) {
                        Mage::log($e->getMessage());
                    }
                    $item->setData($name, 'menu/' . $result['file']);
                }
                elseif (isset($data[$name]) && is_array($data[$name])) {
                    if (isset($data[$name]['delete']) && $data[$name]['delete'] === "1") {
                        $item->setData($name, null);
                    } else {
                        $item->setData($name, $data[$name]['value']);
                    }
                }
            }
        }
    }

    protected function _sortMenu($item, $sorting)
    {
        if ($item->getParent()) {
            $filter = array('eq' => $item->getParent());
        } else {
            $filter = array('null' => true);
        }

        $siblings = Mage::getModel('menu/menu')
            ->getCollection()
            ->addFieldToFilter('parent', $filter);

        $sort = Mage::getModel('menu/menu')->load(isset($sorting['before']) ? $sorting['before'] : $sorting['after'])->getSort();
        if (isset($sorting['after'])) {
            $sort++;
        }

        foreach ($siblings as $sibling) {
            if ($sibling->getSort() >= $sort) {
                $sibling->setSort($sibling->getSort()+1);
                $sibling->save();
            }
        }
        $item->setSort($sort);
    }

    /**
     * Product grid for AJAX request
     */
    public function productGridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Load layout by handles(s)
     *
     * @param   string|null|bool $handles
     * @param   bool $generateBlocks
     * @param   bool $generateXml
     * @return  Mage_Core_Controller_Varien_Action
     */
    public function loadLayout($handles = null, $generateBlocks = true, $generateXml = true)
    {
        $this->getLayout()->getUpdate()->addHandle('default');

        // if handles were specified in arguments load them first
        if (false!==$handles && ''!==$handles) {
            $this->getLayout()->getUpdate()->addHandle($handles);
        }

        // add default layout handles for this action
        $this->addActionLayoutHandles();

        $this->loadLayoutUpdates();

        if (!$generateXml) {
            return $this;
        }
        $this->generateLayoutXml();

        if (!$generateBlocks) {
            return $this;
        }
        $this->generateLayoutBlocks();
        $this->_isLayoutLoaded = true;

        $this->_initLayoutMessages('adminhtml/session');

        return $this;
    }
}