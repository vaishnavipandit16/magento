<?php 

class Ccc_Order_Block_Adminhtml_Order_Cart_Main_ShippingAddress extends Mage_Adminhtml_Block_Template
{
	public function _construct()
	{
		$this->setTemplate('order/cart/main/shippingaddress.phtml');
	}

    public function setCart(Ccc_Order_Model_Order_Cart $cart)
    {
        $this->cart = $cart;
        return $this;
    }

    public function getCart()
    {
       if(!$this->cart){
           throw new Exception('Cart not found');
       }
       return $this->cart;
    }
    
    public function getShippingAddress()
    {
        $address = $this->getCart()->getShippingAddress();
        if($address->getId()){
            return $address;
        }
        $customerAddress = $this->getCart()->getCustomer()->getShippingAddress();
        if($customerAddress==null){
            return $address;
        }
        return $customerAddress;
    }
}


?>