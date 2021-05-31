<?php  

class Ccc_Order_Block_Adminhtml_Order_Cart_Payment extends Mage_Adminhtml_Block_Template
{
	public function _construct()
	{
		$this->setTemplate('order/cart/payment.phtml');
	}

	public function getPaymentMethods()
	{
		$paymentMethods = Mage::getModel('payment/config')->getActiveMethods();
		unset($paymentMethods['paypal_billing_agreement']);
		unset($paymentMethods['checkmo']);
		unset($paymentMethods['free']);
		return $paymentMethods;
	}
}

?>