<?php 
class Ccc_Order_Model_Resource_Order_Cart_Item extends Mage_Core_Model_Resource_Db_Abstract
{
	function _construct()
	{
		$this->_init('order/cart_item','cart_item_id');
	}
}

?>