<?php
class Ccc_Salesman_Block_Adminhtml_Salesman_Edit_Form extends Mage_Adminhtml_Block_Widget_Form{
    protected function _prepareForm(){
        $form = new Varien_Data_Form(
            array(
                'id'=>'edit_form',
                'action'=>$this->getUrl('*/*/save',array('id'=>$this->getRequest()->getParam('id'))),
                'method'=>'post'
            )
        );
        
        $form->setUseContainer(true);
        $this->setForm($form);

        $fieldset = $form->addFieldset('salesman_form',array('legend'=> Mage::helper('salesman')->__('Salesman Information')));

        $fieldset->addField('code','text',array(
            'label'=>Mage::helper('salesman')->__('Code'),
            'class'=>'required-entry',
            'required'=>true,
            'name'=>'code'));

        $fieldset->addField('name','text',array(
            'label'=>Mage::helper('salesman')->__('Name'),
            'class'=>'required-entry',
            'required'=>true,
            'name'=>'name'));

        $fieldset->addField('email','text',array(
            'label'=>Mage::helper('salesman')->__('Email'),
            'class'=>'required-entry',
            'required'=>true,
            'name'=>'email'));

        $fieldset->addField('mobile','text',array(
            'label'=>Mage::helper('salesman')->__('Mobile'),
            'class'=>'required-entry',
            'required'=>true,
            'name'=>'mobile'));

        if(Mage::getSingleton('adminhtml/session')->getSalesmanData()){
            $form->setValues(Mage::getSingleton('adminhtml/session')->getSalesmanData());
            Mage::getSingleton('adminhtml/session')->setSalesmanData(null);
        }
        elseif(Mage::registry('salesman_data')){
            $form->setValues(Mage::registry('salesman_data')->getData());
        }
        return parent::_prepareForm();
    }
}
?>