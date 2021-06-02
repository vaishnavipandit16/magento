<?php  

class Ccc_Order_Block_Adminhtml_Order_Cart_Main_Shipping extends Mage_Adminhtml_Block_Template
{
	public function _construct()
	{
		$this->setTemplate('order/cart/main/shipping.phtml');
	}

	public function getShippingMethods()
	{
		$shippingMethods = Mage::getSingleton('shipping/config')->getActiveCarriers();
		return $shippingMethods;

		//$shippingMethods = Mage::getSingleton('shipping/config')->getActiveCarriers();
		// echo '<pre>';
		// print_r($shippingMethods);
		// die();
		// foreach($shippingMethods as $code => $shippingMethod){
		// 	$shippingTitle[$code] = Mage::getStoreConfig('carriers/' . $code . '/title');
		// }
		// return $shippingTitle;
	}
}

?>