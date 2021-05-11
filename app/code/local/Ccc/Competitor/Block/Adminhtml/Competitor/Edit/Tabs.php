<?php

class Ccc_Competitor_Block_Adminhtml_Competitor_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
      parent::__construct();
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('competitor')->__('Compititor Information'));
    }
    
    public function getCompetitor()
    {
        return Mage::registry('current_competitor');
    }

    protected function _beforeToHtml()
    {

        $competitor= $this->getCompetitor();

        if (!($setId = $competitor->getAttributeSetId())) {
            $setId = $this->getRequest()->getParam('set', null);
        }
        
        if($setId){

        $competitorAttributes = Mage::getResourceModel('competitor/competitor_attribute_collection')
            ->setAttributeSetFilter($setId)
            ->load();
            
        if (!$this->getCompetitor()->getId()) {
            foreach ($competitorAttributes as $attribute) {
                $default = $attribute->getDefaultValue();
                if ($default != '') {
                    $this->getCompetitor()->setData($attribute->getAttributeCode(), $default);
                }
            }
        }

        //$attributeSetId = $this->getCompetitor()->getResource()->getEntityType()->getDefaultAttributeSetId();

        $groupCollection = Mage::getResourceModel('eav/entity_attribute_group_collection')
            ->setAttributeSetFilter($setId)
            ->setSortOrder()
            ->load();

        $defaultGroupId = 0;
        foreach ($groupCollection as $group) {
            if ($defaultGroupId == 0 or $group->getIsDefault()) {
                $defaultGroupId = $group->getId();
            }

        }	
    
        foreach ($groupCollection as $group) {
            $attributes = array();
            foreach ($competitorAttributes as $attribute) {

                if ($this->getCompetitor()->checkInGroup($attribute->getId(),$setId, $group->getId())) {
                    $attributes[] = $attribute;
                }
            }
            if (!$attributes) {
                continue;
            }

            $active = $defaultGroupId == $group->getId();
            $block = $this->getLayout()->createBlock('competitor/adminhtml_competitor_edit_tab_attributes')
                ->setGroup($group)
                ->setAttributes($attributes)
                ->setAddHiddenFields($active)
                ->toHtml();
            $this->addTab('group_' . $group->getId(), array(
                'label'     => Mage::helper('competitor')->__($group->getAttributeGroupName()),
                'content'   => $block,
                'active'    => $active
            ));
        }
        }
        else{
            $this->addTab('set', array(
                'label'     => Mage::helper('competitor')->__('Settings'),
                'content'   => $this->getLayout()
                    ->createBlock('competitor/adminhtml_competitor_edit_tab_settings')->toHtml(),
                'active'    => true
            ));
        }
      return parent::_beforeToHtml();
    }
}