<?php

class Ccc_Order_Model_Customer extends Mage_Customer_Model_Customer{
    
    protected $billingAddress = null;
    protected $shippingAddress = null;
    
    public function setBillingAddress(Mage_Customer_Model_Address $billingAddress)
    {
       $this->billingAddress = $billingAddress;
       return $this;
    }
    
    public function getBillingAddress()
    {
        if($this->billingAddress){
            return $this->billingAddress;
        }
        if(!$this->getId()){
            return false;
        }
        $addressId = $this->getResource()->getAttribute('default_billing')->getFrontend()->getValue($this);
        $address = Mage::getModel('customer/address');

        $addressCollection = $address->getCollection();
        $addressCollection->addAttributeToSelect(['street','region','city','postcode','country_id'],'inner');
        $addressCollection->getSelect()
                    ->reset(Zend_Db_Select::COLUMNS)
                    ->columns(['e.entity_id','street'=>'at_street.value','region'=>'at_region.value','city'=>'at_city.value','postcode'=>'at_postcode.value','country_id'=>'at_country_id.value'])
                    ->where('e.entity_id = ?',$addressId);
        $id = $addressCollection->getData()[0]['entity_id'];
        $address = $address->load($id);
        return $address;
    }

    public function setShippingAddress(Mage_Customer_Model_Address $shippingAddress)
    {
       $this->shippingAddress = $shippingAddress;
       return $this;
    }

    public function getShippingAddress()
    {
        if($this->shippingAddress){
            return $this->shippingAddress;
        }
        if(!$this->getId()){
            return false;
        }
        $addressId = $this->getResource()->getAttribute('default_shipping')->getFrontend()->getValue($this);
        $address = Mage::getModel('customer/address');

        $addressCollection = $address->getCollection();
        $addressCollection->addAttributeToSelect(['street','region','city','postcode','country_id'],'inner');
        $addressCollection->getSelect()
                    ->reset(Zend_Db_Select::COLUMNS)
                    ->columns(['e.entity_id','street'=>'at_street.value','region'=>'at_region.value','city'=>'at_city.value','postcode'=>'at_postcode.value','country_id'=>'at_country_id.value'])
                    ->where('e.entity_id = ?',$addressId);
        $id = $addressCollection->getData()[0]['entity_id'];
        $address = $address->load($id);
        return $address;
    }
}