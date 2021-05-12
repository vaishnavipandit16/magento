<?php

class Ccc_Vendor_Block_Marketplace_Product_Edit_Tabs extends Mage_Core_Block_Template
{
    protected $tabs = [];

    protected function getVendor()
    {
        return Mage::getSingleton('vendor/session')->getVendor();
    }

    public function addTab($key, $tab = [])
    {
        $this->tabs[$key] = $tab;
        return $this;
    }

    public function getTabs()
    {
        return $this->tabs;
    }

    public function getVendorproduct()
    {
        return Mage::registry('current_vendorproduct');
    }

    public function prepareTab()
    {
        // $ven = $this->getVendor();
        // echo '<pre>';
        // print_r($ven);
        // die();
        $vendorId = $this->getVendor()->getId();
        $vendorProduct = Mage::getModel('vendor/product');
        $attributeSetId = $vendorProduct->getResource()->getEntityType()->getDefaultAttributeSetId();
        $entityTypeId = $vendorProduct->getResource()->getEntityType()->getId();

        $vendorProductAttributeGroup = Mage::getResourceModel('vendor/product_attribute_group_collection');
        $vendorProductAttributeGroup->getSelect()->where('main_table.entity_id = ' . $vendorId);
        $vendorProductAttributeGroup = $vendorProductAttributeGroup->load();

        $vendorProductAttributes = Mage::getModel('vendor/resource_product_attribute_collection')
            ->addFieldToFilter('attribute_code', array('like' => '%' . $vendorId . '%'));

        $vendorProductAttributes->getSelect()->joinLeft(
            array('product_attribute' => 'eav_entity_attribute'),
            'product_attribute.attribute_id = main_table.attribute_id',
            array('attribute_group_Id')
        );

        $vendorProductAttributes = $vendorProductAttributes->load();
        // echo '<pre>';
        // print_r($vendorProductAttributes->getData());
        // die();

        $vendorProductDefaultAttributes = Mage::getResourceModel('eav/entity_attribute_collection');
        $vendorProductDefaultAttributes->getSelect()
            ->join(
                array('attribute' => 'eav_entity_attribute'),
                'attribute.attribute_id = main_table.attribute_id',
                array('*')
            )->where("main_table.entity_type_id = {$entityTypeId} AND main_table.is_user_defined = 0 AND main_table.is_required = 1 AND attribute.attribute_set_id = {$attributeSetId}");

        $vendorProductDefaultAttributes = $vendorProductDefaultAttributes->load();
        // echo '<pre>';
        // print_r($vendorProductDefaultAttributes->getItems());
        // die();
        
        $vendorProductAttributes = array_merge($vendorProductAttributes->getItems(), $vendorProductDefaultAttributes->getItems());

        // if (!$this->getVendorproduct()->getId()) {
        //     foreach ($vendorProductAttributes as $attribute) {
        //         if ($attribute->getDefaultValue() != '') {
        //             $this->getVendorproduct()->setData($attribute->getAttributeCode(), $attribute->getDefaultValue());
        //         }
        //     }
        // }

        $vendorProductAttributeDefaultGroup = Mage::getResourceModel('eav/entity_attribute_group_collection');
        $vendorProductAttributeDefaultGroup->setAttributeSetFilter($attributeSetId)
            ->getSelect()
            ->where("attribute_group_name REGEXP '^[A-z]' ");

        $groupCollection = array_merge($vendorProductAttributeGroup->getItems(), $vendorProductAttributeDefaultGroup->getItems());
        // echo '<pre>';
        // print_r($groupCollection);
        // die();

        $defaultGroupId = 0;
        foreach ($groupCollection as $group) {
            if ($defaultGroupId == 0 or $group->getIsDefault()) {
                $defaultGroupId = $group->getId();
            }
        }
        // echo '<pre>';
        // print_r($defaultGroupId);
        // die();

        foreach ($groupCollection as $group) {
            $attributes = array();
            foreach ($vendorProductAttributes as $attribute) {
                if ($this->getVendor()->checkInGroup($attribute->getId(), $attributeSetId, $group->getAttributeGroupId())) {
                    $attributes[] = $attribute;
                }
            }

            if (!$attributes) {
                continue;
            }

            //$active = $defaultGroupId == $group->getAttributeGroupId();
            $block = $this->getLayout()->createBlock('vendor/marketplace_product_edit_tabs_attributes')
                ->setGroup($group)
                ->setAttributes($attributes)
                //->setAddHiddenFields($active)
                ->toHtml();

            $this->addTab('group_' . $group->getId(), array(
                'label' => Mage::helper('vendor')->__($group->getAttributeGroupName()),
                'content' => $block,
                //'active' => $active,
            ));
        }
    }
    public function getBackUrl()
    {
        return $this->getUrl('*/*/grid');
    }
}