<?php
 
class Ccc_Vendor_Block_Marketplace_Product_Edit_Setting extends Mage_Core_Block_Template{
    public function __construct()
    {
       $this->setTemplate('vendor/marketplace/product/edit/setting.phtml');
    }

    public function getAttriuteSets()
    {
        $entityType = Mage::getModel('vendor/vendor')->getResource()->getEntityType();
        return Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter($entityType->getId())
            ->load()
            ->toOptionArray();
    }

    protected function _prepareLayout()
    {
        $this->setChild('continue_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('example')->__('Continue'),
                    'onclick'   => "setSettings('".$this->getContinueUrl()."','attribute_set_id')",
                    'class'     => 'save'
                    )
                )
                );
        return parent::_prepareLayout();
    }

    public function getContinueUrl()
    {
        return $this->getUrl('*/*/new', array(
            '_current'  => true,
            'set'       => '{{attribute_set}}'
        ));
    }

}

?>