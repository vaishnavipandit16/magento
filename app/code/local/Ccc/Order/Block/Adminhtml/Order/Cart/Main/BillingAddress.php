<?php 

class Ccc_Order_Block_Adminhtml_Order_Cart_Main_BillingAddress extends Mage_Adminhtml_Block_Template
{
    protected $cart = null;
	public function _construct()
	{
		$this->setTemplate('order/cart/main/billingaddress.phtml');
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