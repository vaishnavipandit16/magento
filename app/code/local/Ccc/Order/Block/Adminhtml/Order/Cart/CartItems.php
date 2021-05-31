<?php  

class Ccc_Order_Block_Adminhtml_Order_Cart_CartItems extends Mage_Adminhtml_Block_Template
{
	public function _construct()
	{
		$this->setTemplate('order/cart/cartitems.phtml');
	}

	public function getCartItemData(){
		$cartId = Mage::getModel('order/session')->getCartId();
		$collection = Mage::getModel('order/order_cart_item')->getCollection();
		$collection->addFieldToFilter('cart_id',['eq' => $cartId]);
		return $collection;
	}
}

?>