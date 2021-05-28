<?php 

class Ccc_Order_Block_Adminhtml_Order_Cart_ShippingAddress extends Mage_Adminhtml_Block_Template
{
	public function _construct()
	{
		$this->setTemplate('order/cart/shippingaddress.phtml');
	}

	public function getShippingAddress()
    {
        $customerId = Mage::getModel('order/session')->getCustomerId();
        $cartId = Mage::getModel('order/session')->getCartId();
        $cartShippingAddress = Mage::getModel('order/order_cart_address');
        $cartShippingcollection = $cartShippingAddress->getCollection();
        $select = $cartShippingcollection->getSelect()
                ->reset(Zend_Db_Select::FROM)
                ->reset(Zend_Db_Select::COLUMNS)
                ->from('cart_address')
                ->where('cart_id = ?',$cartId)
                ->where('address_type=?','shipping');

        $cartShippingAddress = $cartShippingcollection->fetchItem($select);
        if($cartShippingAddress){
            return $cartShippingAddress;
        }
        else if($customerId){
            $customer = Mage::getModel('customer/customer')->load($customerId);
            $customerShippingAddress = $customer->getDefaultShippingAddress();
            return $customerShippingAddress;
        }
        else{
           return Mage::getModel('order/order_cart_address')->getCollection();
        } 
    }
}


?>