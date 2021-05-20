<?php
 
class Ccc_Vendor_Block_Marketplace_Product_Attribute_Form extends Mage_Eav_Block_Adminhtml_Attribute_Edit_Options_Abstract{

    public function getSaveUrl(){
        return $this->getUrl('*/marketplace/saveattribute');
    }

    public function getAttributeGroups(){
        $id = Mage::getModel('vendor/session')->getId();
        $collection = Mage::getResourceModel('vendor/product_attribute_group_collection')
                ->addFieldToFilter('entity_id',array("eq"=>$id));
        return $collection;
    }

    public function getAttributeSelectedGroup(){
        $id = $this->getRequest()->getParam('id');
        $conn = Mage::getSingleton('core/resource')->getConnection('core_write');
        $sql = "select attribute_group_id from eav_entity_attribute where attribute_id = $id";
        $data = $conn->fetchAll($sql);
        return $data[0]['attribute_group_id'];
        
    }

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