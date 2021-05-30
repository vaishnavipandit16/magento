<?php  

class Ccc_Order_Block_Adminhtml_Order_Cart_Main extends Mage_Adminhtml_Block_Template
{
	protected $cart = null;
	public function _construct()
	{
		$this->setTemplate('order/cart/main.phtml');
	}

	public function setCart(Ccc_Order_Model_Order_Cart $cart)
    {
        $this->cart = $cart;
        return $this;
    }
    public function getCart()
    {
        if(!$this->cart){
            return false;
        }
        return $this->cart;
    }
}

?>