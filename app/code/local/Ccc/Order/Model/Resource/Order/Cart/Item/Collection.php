<?php

class Ccc_Order_Model_Resource_Order_Cart_Item_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract{

	protected function _construct(){
		$this->_init('order/order_cart_item');
	}
}

?>