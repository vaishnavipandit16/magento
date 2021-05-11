<?php

class Ccc_Competitor_Block_Adminhtml_Competitor_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
    	$this->_blockGroup = 'competitor';
        $this->_controller = 'adminhtml_competitor_attribute';
        $this->_headerText = Mage::helper('competitor')->__('Manage Attributes');
        $this->_addButtonLabel = Mage::helper('competitor')->__('Add New Attribute');
        parent::__construct();
    }

}