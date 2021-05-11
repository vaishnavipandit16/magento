<?php

class Ccc_Competitor_Block_Adminhtml_Competitor_Edit_Tab_Attributes extends Mage_Adminhtml_Block_Widget_Form
{

    public function getCompetitor()
    {
        return Mage::registry('current_competitor');
    }


    protected function _prepareLayout()
    {
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        parent::_prepareLayout();
           
    }

    protected function _prepareForm()
    {

        $group = $this->getGroup();

        $attributes = $this->getAttributes();

        $form = new Varien_Data_Form();
        $this->setForm($form);

        $form->setDataObject($this->getCompetitor());
        $form->setHtmlIdPrefix('group_' . $group->getId());
        $form->setFieldNameSuffix('account');
        $fieldset = $form->addFieldset('fieldset_group_' . $group->getId(), array(
            'legend'    => Mage::helper('competitor')->__($group->getAttributeGroupName()),
            'class'     => 'fieldset',
        ));


        $this->_setFieldset($attributes, $fieldset);

        $form->addValues($this->getCompetitor()->getData());

        return parent::_prepareForm();
    }


}