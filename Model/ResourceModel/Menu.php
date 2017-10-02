<?php

namespace OuterEdge\Menu\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use OuterEdge\Menu\Model\Menu as MenuModel;

class Menu extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('menu', 'menu_id');
    }

    /**
     * Load menu resource by code
     *
     * @param Menu $menu
     * @param string $code
     * @return $this
     */
    public function loadByCode(MenuModel $menu, $code)
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
