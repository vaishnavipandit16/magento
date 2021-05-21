<?php

class Ccc_Vendor_Model_Order extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('sales/order_item', 'item_id');
    }
}