<?php
class Ccc_Order_Block_Adminhtml_Order_Customer_Form extends Mage_Core_Block_Template{
    
    public function __construct(){
        $this->setTemplate('order/customer/form.phtml');
    }

    public function getCountries()
    {
        return Mage::getModel('adminhtml/system_config_source_country')->toOptionArray();
    }

    // public function getRegions()
    // {
    //     return Mage::getModel('adminhtml/system_config_source_allRegion')->toOptionArray();
    // }

    public function getBillingAddress()
    {
        $address = $this->getCart()->getBillingAddress();
        if($address->getId()){
            return $address;
        }
        $customerAddress = $this->getCart()->getCustomer()->getBillingAddress();
        if($customerAddress==null){
            return $address;
        }
        return $customerAddress;
    }
}
?>