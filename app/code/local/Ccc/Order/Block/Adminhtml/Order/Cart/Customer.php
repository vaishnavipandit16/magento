<?php 

class Ccc_Order_Block_Adminhtml_Order_Cart_Customer extends Mage_Adminhtml_Block_Template
{
	
	public function _construct()
	{
		$this->setTemplate('order/cart/customer.phtml');
	}

	public function getCustomers()
	{
		$customers = Mage::getModel('customer/customer')->getCollection();
		$customers->addNameToSelect();
		return $customers;
	}
}


?>