<?php
class Ccc_Salesman_Block_Adminhtml_Salesman extends Mage_Adminhtml_Block_Widget_Grid_Container{
    public function __construct(){
        $this->_controller = 'adminhtml_salesman';
        $this->_blockGroup = 'ccc_salesman';
        $this->_headerText = Mage::helper('salesman')->__('Manage Salesman');
        $this->_addButtonLabel = Mage::helper('salesman')->__('Add Salesman');
        parent::__construct();
    }
}
?>