<?php

class Edge_Menu_Adminhtml_MenuController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function dataJsonAction()
    {
        $id = $this->getRequest()->getParam('id');
        $type = $this->getRequest()->getParam('type');
        $parent = $this->getRequest()->getParam('parent');

        if($type === 'product'){
            $data = $this->productJson($id);
        } else if($type === 'category'){
            $data = $this->categoryJson($id);
        } else if($type === 'cms'){
            $data = $this->cmsJson($id);
        }

        $data['type'] = $type;
        if($parent){
            $data['parent'] = $parent;
        }

        $jsonData = Mage::helper('core')->jsonEncode($data);
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }

    public function insertDataAction()
    {
        $data = $this->getRequest()->getParam('data');
        Mage::getModel('menu/menu')->setData($data)->save();
    }

    public function updateDataAction()
    {
        $id = $this->getRequest()->getParam('id');
        $data = $this->getRequest()->getParams();

        $item = Mage::getModel('menu/menu')->load($id);
        $item->setTitle($data['title']);

        if (isset($data['url']) && $data['url'] !== '') {
            $item->setUrl($data['url']);
        }

        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != ''){
            try {
                $uploader = new Mage_Core_Model_File_Uploader('image');
                $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png','svg'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $result = $uploader->save(Mage::getBaseDir('media') . DS . 'menu' . DS, $_FILES['image']['name']);

            } catch (Exception $e){}

            $item->setImage('menu/' . $result['file']);
        }

        if (isset($data['is_html']) && $data['is_html'] === 'on'){
            $item->setIsHtml(1);
            $item->setHtml($data['html']);
        }
        else {
            $item->setIsHtml(0);
        }

        $item->save();
        return true;
    }

    public function createCustomAction()
    {
        $data = $this->getRequest()->getParams();

        $item = Mage::getModel('menu/menu');
        $item->setTitle($data['title']);
        $item->setType($data['type']);
        $item->setUrl($data['url']);

        if (isset($data['is_html']) && $data['is_html'] === 'on'){
            $item->setIsHtml(1);
            $item->setHtml($data['html']);
        }

        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != ''){
            try {
                $uploader = new Mage_Core_Model_File_Uploader('image');
                $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png','svg'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $result = $uploader->save(Mage::getBaseDir('media') . DS . 'menu' . DS, $_FILES['image']['name']);

            } catch (Exception $e){}

            $item->setImage('menu/' . $result['file']);
        }

        $jsonData = Mage::helper('core')->jsonEncode($item->getData());
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }

    public function deleteDataAction()
    {
        $id = $this->getRequest()->getParam('id');

        $item = Mage::getModel('menu/menu')->load($id);
        $item->delete();

        return true;
    }

    public function deleteImageAction()
    {
        $id = $this->getRequest()->getParam('id');

        $item = Mage::getModel('menu/menu')->load($id);
        $item->setImage(null);
        $item->save();

        return true;
    }

    public function getItemAction()
    {
        $id = $this->getRequest()->getParam('id');

        $item = Mage::getModel('menu/menu')->load($id);

        $jsonData = Mage::helper('core')->jsonEncode($item->getData());
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody($jsonData);
    }

    public function changeParentAction()
    {
        $id = $this->getRequest()->getParam('id');
        $parent = $this->getRequest()->getParam('parent');
        if(!$parent){
            $parent = null;
        }

        $item = Mage::getModel('menu/menu')->load($id);
        $item->setParent($parent);

        $otherItem = Mage::getModel('menu/menu')
            ->getCollection()
            ->addFieldToFilter('parent', $parent ? array('eq' => $parent) : array('null' => true))
            ->addOrder('sort', 'DESC')
            ->setPageSize(1)
            ->getFirstItem();
        if(!$otherItem->getData()){
            $item->setSort(1);
        } else {
            $item->setSort($otherItem->getSort()+1);
        }

        $item->save();
        return true;
    }

    public function sortUpAction()
    {
        $id = $this->getRequest()->getParam('id');

        $item = Mage::getModel('menu/menu')->load($id);
        $parent = $item->getParent() ? array('eq' => $item->getParent()) : array('null' => true);

        $otherItem = Mage::getModel('menu/menu')
            ->getCollection()
            ->addFieldToFilter('parent', $parent)
            ->addFieldToFilter('sort', array('lt' => $item->getSort()))
            ->addOrder('sort', 'DESC')
            ->setPageSize(1)
            ->getFirstItem();

        if (!$otherItem->getData()){
            return;
        }

        $otherSort = $otherItem->getSort();
        $otherItem->setSort($item->getSort());
        $item->setSort($otherSort);

        $otherItem->save();
        $item->save();

        return true;
    }

    public function sortDownAction()
    {
        $id = $this->getRequest()->getParam('id');

        $item = Mage::getModel('menu/menu')->load($id);
        $parent = $item->getParent() ? array('eq' => $item->getParent()) : array('null' => true);

        $otherItem = Mage::getModel('menu/menu')
            ->getCollection()
            ->addFieldToFilter('parent', $parent)
            ->addFieldToFilter('sort', array('gt' => $item->getSort()))
            ->addOrder('sort', 'ASC')
            ->setPageSize(1)
            ->getFirstItem();

        if (!$otherItem->getData()){
            return;
        }

        $otherSort = $otherItem->getSort();
        $otherItem->setSort($item->getSort());
        $item->setSort($otherSort);

        $otherItem->save();
        $item->save();

        return true;
    }

    public function categoryJson($id)
    {
        $category = Mage::getModel('catalog/category')->load($id);
        return array(
            'title' => $category->getName(),
            'entity_id' => $id
        );
    }

    public function productJson($id)
    {
        $product = Mage::getModel('catalog/product')->load($id);
        return array(
            'title' => $product->getName(),
            'entity_id' => $id
        );
    }

    public function cmsJson($id)
    {
        $cms = Mage::getModel('cms/page')->load($id);
        return array(
            'title' => $cms->getTitle(),
            'entity_id' => $id
        );
    }

    /**
     * Product grid for AJAX request
     */
    public function productGridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}