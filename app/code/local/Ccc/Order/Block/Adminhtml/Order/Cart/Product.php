<?php 

class Ccc_Order_Block_Adminhtml_Order_Cart_Product extends Mage_Adminhtml_Block_WIdget_Grid_Container
{	
	public function __construct()
	{
		$this->_blockGroup = "order";
		$this->_controller = 'adminhtml_order_cart_product';
		$this->_headerText = "Select Product";
		parent::__construct();
	}
	
}


?>