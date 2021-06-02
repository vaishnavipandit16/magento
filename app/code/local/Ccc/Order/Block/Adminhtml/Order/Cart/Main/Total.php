<?php  
class Ccc_Order_Block_Adminhtml_Order_Cart_Main_Total extends Mage_Adminhtml_Block_Template
{
    protected $cart = null;
    protected $subtotal = null;
    
	public function _construct()
	{
		$this->setTemplate('order/cart/main/total.phtml');
	}

    protected $total = [
        'subtotal' => 0,
        'shippingAmount' =>0,
        'grandTotal' =>0
    ];
    
    public function setCart(Ccc_Order_Model_Order_Cart $cart)
    {
        $this->cart = $cart;
        return $this;
    }
    
    public function getCart()
    {
       if(!$this->cart){
           throw new Exception('Cart not found');
       }
       return $this->cart;
    }

    public function calculateSubTotal()
    {
       $items = $this->cart->getCartItems();
       foreach($items->getData() as $key => $item){
           $this->total['subtotal'] += $item['quantity']*$item['price'];
       }
    }
    
    public function getShippingCharges()
    {
       $this->total['shippingAmount'] = $this->cart->getShippingAmount();
        
    }
    
    public function calculateGrandTotal()
    {
        $this->total['grandTotal'] = $this->total['subtotal'] + $this->total['shippingAmount'];
    }
    
    public function calculateTotal()
    {
        $this->calculateSubTotal();
        $this->getShippingCharges();
        $this->calculateGrandTotal();
        return $this->total;
    }
} 
?>