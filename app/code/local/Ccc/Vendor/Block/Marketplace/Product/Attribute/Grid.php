<?php
class Ccc_Vendor_Block_Marketplace_Product_Attribute_Grid extends Mage_Core_Block_Template{
   public function getSaveUrl(){
       return $this->getUrl('*/marketplace/attributegrid');
   }

    public function getAttributes(){
        $vendorId = Mage::getModel('vendor/session')->getId();
        $collection = Mage::getModel('vendor/resource_product_attribute_collection')
            ->addFieldToFilter('attribute_code',array('like'=>'%'.$vendorId));
        return $collection;
    }

    public function getAddUrl(){
        return $this->getUrl('*/*/createattribute');
    }
}
?>