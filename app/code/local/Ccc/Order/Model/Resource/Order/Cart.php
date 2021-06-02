<?php 
class Ccc_Order_Model_Resource_Order_Cart extends Mage_Core_Model_Resource_Db_Abstract
{
	function _construct()
	{
		$this->_init('order/cart','cart_id');
	}

}

?>