<?php

class Edge_Menu_Model_Resource_Menu extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('menu/menu', 'id');
    }
}
