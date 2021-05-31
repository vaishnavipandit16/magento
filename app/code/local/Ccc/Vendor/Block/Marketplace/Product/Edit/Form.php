<?php

class Ccc_Vendor_Block_Marketplace_Product_Edit_Form extends Mage_Core_Block_Template
{   
    public function __construct()
    {
        $this->setTemplate('vendor/marketplace/product/edit/form.phtml');
    }

    public function saveUrl(){
        return $this->getUrl('*/product/saveproduct',array('id' => $this->getRequest()->getParam('id')));
    }

    public function deleteUrl(){
        return $this->getUrl('*/product/deleteproduct');
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

    
}