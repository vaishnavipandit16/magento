<?php

class Ccc_Competitor_Block_Adminhtml_Competitor_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('competitor_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('competitor')->__('Attribute Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('main', array(
            'label'     => Mage::helper('competitor')->__('Properties'),
            'title'     => Mage::helper('competitor')->__('Properties'),
            'content'   => $this->getLayout()->createBlock('competitor/adminhtml_competitor_attribute_edit_tab_main')->toHtml(),
            'active'    => true
        ));

        $model = Mage::registry('entity_attribute');

        $this->addTab('labels', array(
            'label'     => Mage::helper('competitor')->__('Manage Label / Options'),
            'title'     => Mage::helper('competitor')->__('Manage Label / Options'),
            'content'   => $this->getLayout()->createBlock('competitor/adminhtml_competitor_attribute_edit_tab_options')->toHtml(),
        ));
        
        if ('select' == $model->getFrontendInput()) {
            $this->addTab('options_section', array(
                'label'     => Mage::helper('competitor')->__('Options Control'),
                'title'     => Mage::helper('competitor')->__('Options Control'),
                'content'   => $this->getLayout()->createBlock('competitor/adminhtml_competitor_attribute_edit_tab_options')->toHtml(),
            ));
        }

        return parent::_beforeToHtml();
    }

}