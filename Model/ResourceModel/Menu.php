<?php

namespace OuterEdge\Menu\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Menu extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('menu', 'menu_id');
    }

    public function loadByCode(\OuterEdge\Menu\Model\Menu $menu, $code)
    {
        $connection = $this->getConnection();
        $bind = ['code' => $code];
        $select = $connection->select()->from(
            $this->getEntityTable(),
            [$this->getEntityIdField()]
        )->where(
            'code = :code'
        );

        $menuId = $connection->fetchOne($select, $bind);
        if ($menuId) {
            $this->load($menu, $menuId);
        } else {
            $menu->setData([]);
        }

        return $this;
    }
}
