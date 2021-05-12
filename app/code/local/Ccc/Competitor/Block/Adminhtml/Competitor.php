<?php
class Ccc_Competitor_Block_Adminhtml_Competitor extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'competitor';
        $this->_controller = 'adminhtml_competitor';
        $this->_headerText = $this->__('Competitor Grid');
        parent::__construct();
    }
}