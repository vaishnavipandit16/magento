<?php  

class Ccc_Order_Block_Adminhtml_Order_Invoice_BillingAddress extends Mage_Adminhtml_Block_Template
{
    protected $order = null;
	public function _construct()
	{
		$this->setTemplate('order/invoice/billingaddress.phtml');
	}

	public function setOrder(Ccc_Order_Model_Order $order)
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder()
    {
       if(!$this->order){
           throw new Exception('Order not found');
       }
       return $this->order;
    }
    
    public function getBillingAddress()
    {
        $billingAddress = $this->getOrder()->getBillingAddress();
        return $billingAddress;
    }
}

?>