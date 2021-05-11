<?php
class Ccc_Salesman_Block_Adminhtml_Salesman_Edit extends Mage_Adminhtml_Block_Widget_Form_Container{
    public function __construct(){
        parent::__construct();
        $this->_objectId = "id";
        $this->_controller = 'adminhtml_salesman';
        $this->_blockGroup = 'ccc_salesman';
        $this->_updateButton('save','label',Mage::helper('salesman')->__('Save Salesman'));
        $this->_updateButton('delete','label',Mage::helper('salesman')->__('Delete Salesman'));
   }

   public function getHeaderText(){
       if (Mage::registry('salesman_data') && Mage::registry('salesman_data')->getId()){
           return Mage::helper('salesman')->__("Edit Salesman '%s'",$this->escapeHtml(Mage::registry('salesman_data')->getTitle()));
       } else{
           return Mage::helper('salesman')->__('Add Salesman');
       }
   }
}
?>