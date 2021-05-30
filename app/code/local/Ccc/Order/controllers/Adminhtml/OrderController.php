<?php

class Ccc_Order_Adminhtml_OrderController extends Mage_Adminhtml_Controller_Action{

	public function indexAction()
	{
		$this->loadLayout();
		$this->renderLayout();
	}

	public function placeOrderAction()
    {
        try{
            $customerId = $this->getRequest()->getParam('id');
            if(!$customerId){
                throw new Exception('Customer not found');
            }
            $cart = Mage::getModel('order/order_cart')->load($customerId,'customer_id');
            $cartItems = $cart->getCartItems();
            $billingAddress = $cart->getBillingAddress();
            $shippingAddress = $cart->getShippingAddress();

            if($cartItems->count()<=0){
                throw new Exception('Please Add the atleast one item');
            }
            if(!$billingAddress->getData()){
                throw new Exception('Please Add Billing Address');

            }
            if(!$shippingAddress->getData()){
                throw new Exception('Please Add Shipping Address');
            }
            if(!$cart->getPaymentMethodCode()){
                throw new Exception('Please Choose the Payment method');
            }
            if(!$cart->getShippingMethodCode()){
                throw new Exception('Please Choose the Shipping method');
            }
            $total = 0;
            foreach($cartItems->getData() as $key => $item){
                $total += $item['quantity']*$item['price'];
            }
            $total += $cart->getShippingAmount();
            $cart->setTotal($total)->save();

            $customer = $cart->getCustomer()->load($customerId);
            $customerName = $customer->getFirstname().'  '.$customer->getLastname();

            date_default_timezone_set('Asia/Kolkata');
            $orderModel = Mage::getModel('order/order');
            $orderModel->addData($cart->getData())
                        ->setCustomerName($customerName)
                        ->setCreatedAt(date('Y-m-d H:i:s'));
            if(!$orderModel->save()){
                throw new Exception('Unable to save order');
            }

            $orderId = $orderModel->getId();
            $orderAddressModel = Mage::getModel('order/order_address');
            $orderAddressModel->setData($billingAddress->getData())
                        ->setOrderId($orderId);
			
            if(!$orderAddressModel->save()){
                throw new Exception('Unable to save order billing address');
            }

            $orderAddressModel = Mage::getModel('order/order_address');
            $orderAddressModel->addData($shippingAddress->getData())
                        ->setOrderId($orderId);

            if(!$orderAddressModel->save()){
                throw new Exception('Unable to save order shipping address');
            }


            foreach ($cartItems->getData() as $key=>$value){
                $orderItemModel = Mage::getModel('order/order_item');
                $orderItemModel->addData($value)
                    ->setOrderId($orderId)
                    ->setCreatedAt(date('Y-m-d H:i:s'));
                if(!$orderItemModel->save()){
                    throw new Exception('Unable to save order shipping Item');
                }
            } 
            $cart->delete();
            $this->_redirect('*/adminhtml_order/index');
        }
        catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/adminhtml_order_cart/index', ['id' => $customerId]);
        }  
    }

    public function viewAction(){
        $this->loadLayout();
		$this->_title($this->__('order'))->_title($this->__('Invoice'));
        $this->_setActiveMenu('order');
		$order = $this->_getOrder();
		$this->getLayout()->getBlock('invoice')->setOrder($order);
		$this->renderLayout();
    }

    protected function _getOrder(){
		try{
			$orderId = $this->getRequest()->getParam('id');
			
			if(!$orderId){
				throw new Exception('Id not exist');
			}
			$order = Mage::getModel('order/order')->load($orderId);
            
            if(!$order->getId()){
                throw new Exception('Order for request id is not found');
            }
			return $order;

		}catch(Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}	
	}
}

?>