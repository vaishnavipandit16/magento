<?php  

class Ccc_Order_Block_Adminhtml_Order_Cart_Shipping extends Mage_Adminhtml_Block_Template
{
	public function _construct()
	{
		$this->setTemplate('order/cart/shipping.phtml');
	}

	public function getShippingMethods()
	{
		$shippingMethods = Mage::getSingleton('shipping/config')->getActiveCarriers();
		return $shippingMethods;
	}
}

?>