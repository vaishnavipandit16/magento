<?php  

class Ccc_Order_Block_Adminhtml_Order_Invoice_OrderItems extends Mage_Adminhtml_Block_Template
{
	public function _construct()
	{
		$this->setTemplate('order/invoice/orderitems.phtml');
	}

	public function getProductName($id)
	{
		$product = Mage::getModel('catalog/product')->load($id);
		return $product->getName();
	}
}

?>