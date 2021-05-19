<?php

class Ccc_Vendor_Block_Marketplace_Product_Edit_Tabs_Attributes extends Mage_Core_Block_Template
{   
    public $arr = [];
    public function __construct()
    {
        $this->setTemplate('vendor/marketplace/product/edit/tabs/attributes.phtml');
    }

    public function getOptions($attributeId){
        if (!$attributeId){
            return false;
        }
        $connection = Mage::getSingleton('core/resource')->getConnection('core_write');
        $sql = "select `option_id` from `eav_attribute_option` where `attribute_id` = $attributeId";
        $optionsCollection = $connection->fetchAll($sql);
        
        if ($optionsCollection) {
            foreach ($optionsCollection as $key=>$option){
                $optionId = $option['option_id'];
                $sql = "select `option_id`,`value` from `eav_attribute_option_value` where `option_id` = $optionId";
                $options = $connection->fetchAll($sql);
                $arr[] = $options[0];     
            }
        }
       
        return $arr;
    }

    public function getVendorproductData()
    {
        return Mage::registry('current_vendorproduct');
    }
}