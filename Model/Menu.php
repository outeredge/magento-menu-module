<?php

namespace OuterEdge\Menu\Model;

use Magento\Framework\Model\AbstractModel;

class Menu extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('OuterEdge\Menu\Model\ResourceModel\Menu');
    }

    /**
     * Load menu by code
     *
     * @param string $code
     * @return $this
     */
    public function loadByCode($code)
    {
        $this->_getResource()->loadByCode($this, $code);
        return $this;
    }
}
