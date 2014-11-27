<?php

class Edge_Menu_Model_Menu extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('menu/menu');
    }
}
