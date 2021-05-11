<?php
class Ccc_Vendor_Block_Marketplace_Product_Group_Grid extends Mage_Core_Block_Template{
   public function getSaveUrl(){
       return $this->getUrl('*/marketplace/groupgrid');
   }

   public function getGroups(){
       $id = Mage::getModel('vendor/session')->getId();
       $collection = Mage::getResourceModel('vendor/product_attribute_group_collection')
                ->addFieldToFilter('entity_id',array("eq"=>$id));
        return $collection;
   }

   public function getAddUrl(){
       return $this->getUrl('*/*/creategroup');
   }
}

?>