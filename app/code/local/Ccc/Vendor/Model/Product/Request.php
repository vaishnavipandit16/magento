<?php
class Ccc_Vendor_Model_Product_Request extends Mage_Core_Model_Abstract{
    const ENTITY = 'vendor_product_request';

    protected function _construct(){
        parent::_construct();
        $this->_init('vendor/product_request');
    }
}
?>