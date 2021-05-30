<?php
class Ccc_Order_Model_Order extends Mage_Core_Model_Abstract{

    protected $customer = null;
    protected $items = null;
    protected $billingAddress = null;
    protected $shippingAddress = null;
    
    public function _construct(){
        $this->_init('order/order');
    }

    public function setCustomer(Mage_Customer_Model_Customer $customer)
    {
      $this->customer = $customer;
      return $this;
    }

    public function getCustomer()
    {
      if($this->customer){
        return $this->customer;
      }
      $customer = Mage::getModel('customer/customer')->load($this->customer_id);
      $this->setCustomer($customer);
      return $this->customer;
    }

    public function setBillingAddress(Ccc_Order_Model_Order_Address $billingAddress){
      $this->billingAddress = $billingAddress;
      return $this;
  }
  
  public function getBillingAddress()
  {
    if($this->billingAddress){
      return $this->billingAddress;
    }
    if(!$this->orderId){
      return false;
    }
    $address= Mage::getModel('order/order_address');
    $addressCollection= $address->getCollection()
          ->addFieldToFilter('order_id',['eq'=>$this->order_id])
          ->addFieldToFilter('address_type',['eq'=>Ccc_Order_Model_Order_Cart_Address::ADDRESS_TYPE_BILLING]);
   
    $id = $addressCollection->getData()[0]['order_address_id'];
    $address = $address->load($id);
    return $address;

  }
  
  public function setShippingAddress(Ccc_Order_Model_Order_Address $shippingAddress){
      $this->shippingAddress = $shippingAddress;
      return $this;
  }
  
  public function getShippingAddress()
    {
      if($this->shippingAddress){
        return $this->shippingAddress;
      }
      if(!$this->orderId){
        return false;
      }
      $address= Mage::getModel('order/order_address');
      $addressCollection= $address->getCollection()
              ->addFieldToFilter('order_id',['eq'=>$this->order_id])
              ->addFieldToFilter('address_type',['eq'=>Ccc_Order_Model_Order_Cart_Address::ADDRESS_TYPE_SHIPPING]);
      
      $id = $addressCollection->getData()[0]['order_address_id'];
      $address = $address->load($id);
      return $address;

    }

    public function setItems(Ccc_Order_Model_Resource_Order_Item_Collection $items)
    {
      $this->items = $items;
      return $this;
    }
    
    public function getItems()
    { 
      if($this->items){
        return $this->items;
      }
      $items = Mage::getModel('order/order_item')->getCollection();
      $items->addFieldToFilter('order_id',['eq' => $this->order_id]);
      $this->setItems($items);
      return $this->items; 
    }

}
?>