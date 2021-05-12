<?php
 
class Ccc_Vendor_Block_Marketplace_Product_Attribute_Edit extends Mage_Core_Block_Template{

    public function getAttribute(){
       $id = $this->getRequest()->getParam('id');
       $model = Mage::getModel('vendor/resource_eav_productattribute')->load($id);
       return $model;
    }

    public function getEditAttributeUrl(){
        return $this->getUrl('*/marketplace/saveattribute',array("attribute_id" => $this->getRequest()->getParam('id')));
    }

    public function getDeleteAttributeUrl(){
        return $this->getUrl('*/marketplace/deleteattribute',array("attribute_id" => $this->getRequest()->getParam('id')));
    }

}