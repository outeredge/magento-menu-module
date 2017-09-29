<?php

namespace OuterEdge\Menu\Model;

use Magento\Framework\Model\AbstractModel;

class Menu extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('OuterEdge\Menu\Model\ResourceModel\Menu');
    }

    public function loadByCode($code)
    {
        $this->_getResource()->loadByCode($this, $code);
        return $this;
    }
}
