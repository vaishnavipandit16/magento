<?php 

class Ccc_Order_Block_Adminhtml_Order_Cart_Account extends Mage_Adminhtml_Block_Template
{
	
	public function _construct()
	{
		$this->setTemplate('order/cart/account.phtml');
	}

	public function getCustomers()
	{
        $customerId = Mage::getModel('order/session')->getCustomerId();
		$customer = Mage::getModel('customer/customer')->getCollection()
                        ->addFieldToFilter('entity_id',['eq'=>$customerId]);
        $customer->addNameToSelect();
        return $customer;
	}
}


?>