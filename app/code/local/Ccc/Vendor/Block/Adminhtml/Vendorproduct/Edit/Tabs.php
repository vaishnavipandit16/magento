<?php

class Ccc_Vendor_Block_Adminhtml_Vendorproduct_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{


    public function __construct()
    {
      parent::__construct();
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('vendor')->__('Vendor Product Information'));
    }
    
    public function getVendorproduct()
    {
        if (!($this->getData('current_vendorproduct') instanceof Ccc_Vendor_Model_Product)) {
            $this->setData('current_vendorproduct', Mage::registry('current_vendorproduct'));
        }
        return $this->getData('current_vendorproduct');
    }

    protected function _beforeToHtml()
    {

        $vendorproduct= $this->getVendorproduct();

        if (!($setId = $vendorproduct->getAttributeSetId())) {
            $setId = $this->getRequest()->getParam('set', null);
        }
        
        if($setId){

        $vendorAttributes = Mage::getResourceModel('vendor/product_attribute_collection')
            ->setAttributeSetFilter($setId)
            ->load();
        // echo '<pre>';
        // print_r($vendorAttributes->getData());
        // die();
        
        if (!$this->getVendorproduct()->getId()) {
            foreach ($vendorAttributes as $attribute) {
                $default = $attribute->getDefaultValue();
                if ($default != '') {
                    $this->getVendorproduct()->setData($attribute->getAttributeCode(), $default);
                }
            }
        }

        $attributeSetId = $this->getVendorproduct()->getResource()->getEntityType()->getDefaultAttributeSetId();

        $groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
            ->setAttributeSetFilter($attributeSetId)
            ->setSortOrder()
            ->load();
        // echo '<pre>';
        // print_r($groupCollection);
        // die();

        $defaultGroupId = 0;
        foreach ($groupCollection as $group) {
            if ($defaultGroupId == 0 or $group->getIsDefault()) {
                $defaultGroupId = $group->getId();
            }

        }	
    
        foreach ($groupCollection as $group) {
            $attributes = array();
            foreach ($vendorAttributes as $attribute) {

                if ($this->getVendorproduct()->checkInGroup($attribute->getId(),$attributeSetId, $group->getId())) {
                    $attributes[] = $attribute;
                }
            }
            if (!$attributes) {
                continue;
            }

            $active = $defaultGroupId == $group->getId();
            $block = $this->getLayout()->createBlock('vendor/adminhtml_vendorproduct_edit_tab_attributes')
                ->setGroup($group)
                ->setAttributes($attributes)
                ->setAddHiddenFields($active)
                ->toHtml();
            $this->addTab('group_' . $group->getId(), array(
                'label'     => Mage::helper('vendor')->__($group->getAttributeGroupName()),
                'content'   => $block,
                'active'    => $active
            ));
        }
        }
        else{
            $this->addTab('set', array(
                'label'     => Mage::helper('vendor')->__('Settings'),
                'content'   => $this->getLayout()
                    ->createBlock('vendor/adminhtml_vendorproduct_edit_tab_settings')->toHtml(),
                'active'    => true
            ));
        }
      return parent::_beforeToHtml();
    }
}