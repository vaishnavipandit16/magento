<?php
class Ccc_Vendor_Block_Marketplace_Product_Edit extends Mage_Core_Block_Template
{
    public function prepareLayout()
    {
        return Mage::getBlockSingleton('vendor/marketplace_product_edit_form');
    }
}