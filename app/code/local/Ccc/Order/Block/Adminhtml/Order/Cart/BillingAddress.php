<?php 

class Ccc_Order_Block_Adminhtml_Order_Cart_BillingAddress extends Mage_Adminhtml_Block_Template
{
	public function _construct()
	{
		$this->setTemplate('order/cart/billingaddress.phtml');
	}

	public function getBillingAddress()
    {
        $customerId = Mage::getModel('order/session')->getCustomerId();
        $cartId = Mage::getModel('order/session')->getCartId();
        $cartBillingAddress = Mage::getModel('order/order_cart_address');
        $cartBillingcollection = $cartBillingAddress->getCollection();
        $select = $cartBillingcollection->getSelect()
                ->reset(Zend_Db_Select::FROM)
                ->reset(Zend_Db_Select::COLUMNS)
                ->from('cart_address')
                ->where('cart_id = ?',$cartId)
                ->where('address_type=?','billing');

        $cartBillingAddress = $cartBillingcollection->fetchItem($select);
        if($cartBillingAddress){
            return $cartBillingAddress;
        }
        else if($customerId){
            $customer = Mage::getModel('customer/customer')->load($customerId);
            $customerBillingAddress = $customer->getDefaultBillingAddress();
            return $customerBillingAddress;
        }
        else{
           return Mage::getModel('order/order_cart_address');
        } 
    }
}


?>