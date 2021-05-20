<?php
class Ccc_Vendor_Block_Marketplace_Product_Group_Form extends Mage_Core_Block_Template{
   public function getSaveUrl(){
       return $this->getUrl('*/marketplace/savegroup');
   }
}
?>