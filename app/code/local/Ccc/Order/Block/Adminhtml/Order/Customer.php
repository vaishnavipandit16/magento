<?php
class Ccc_Order_Block_Adminhtml_Order_Customer extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct(){
        parent::__construct();
        $this->_controller = 'adminhtml_order_customer';
        $this->_blockGroup = 'order';
        $this->_headerText = 'Please Choose Customer';
        $this->_removeButton('add');
        $this->addButton ('back', [
            'label' =>  'Back',
            'onclick'   => 'setLocation(\'' . $this->getUrl('*/adminhtml_order/index') . '\')',
            'class'     =>  'back'
        ], 0, 1,  'header');
    }

}