<?php 

class Ccc_Order_Adminhtml_Order_CartController extends Mage_Adminhtml_Controller_Action
{

	public function indexAction()
	{
		$this->loadLayout();
		$this->_title($this->__('order'))->_title($this->__('Orders'));
        $this->_setActiveMenu('order');
		$cart = $this->_getCart();
		$this->getLayout()->getBlock('main')->setCart($cart);
		$this->renderLayout();
	}

	public function showcustomerAction(){
		$this->loadLayout();
		$this->renderLayout();
	}
		
	public function getCartAction()
	{
		$id = $this->getRequest()->getPost('customerId');
		$session = Mage::getModel('order/session');
		$session->customerId = $id;
		$cart = Mage::getModel('order/order_cart');
		$cart = $cart->load($id, 'customer_id');
		if (!$cart->getId()) {
			$cart = Mage::getModel('order/order_cart');
			$cart->customerId = $id;
			$cart->save();
		}
		$session->cartId = $cart->cartId;
	}	

	public function addToCartAction(){
		try{
			$customerId = $this->getRequest()->getParam('id');
			if(!$customerId){
				throw new Exception('Customer not Exist');
			}
			$productIds = $this->getRequest()->getParam('product');	
			$cart = $this->_getCart();
			foreach($productIds as $key => $productId){
				$product = Mage::getSingleton('catalog/product')->load($productId);
				$cart->addItemToCart($product,1,true);
			}
			Mage::getSingleton('adminhtml/session')->addSuccess("Item added to cart successfully");
		} catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index', ['id' => $customerId]);		
	}

	protected function _getCart($id = null){
		try{
			if(!$id){
				$id = $this->getRequest()->getParam('id');
			}
			date_default_timezone_set('Asia/Kolkata');
			$customer = Mage::getModel('customer/customer')->load($id);
			if(!$customer->getId()){
				throw new Exception('Customer not exist');
			}
			$cart = Mage::getModel('order/order_cart');
			$cart->load($id,'customer_id');
			if($cart->getId()){
				return $cart;
			}
			$cart->setCustomerId($id)
				->setCreatedAt(date('Y-m-d H:i:s'))
				->save();
			return $cart;
		}catch(Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}	
	}

	public function updateItemAction(){
		try{
			$customerId = $this->getRequest()->getParam('id');
			if(!$customerId){
				throw new Exception("Customer not exist");
			}
			$data = $this->getRequest()->getPost();
			foreach($data['quantity'] as $cartItemId => $quantity){
				$cartItem = Mage::getModel('order/order_cart_item')->load($cartItemId);
				if($quantity == 0){
					$cartItem->delete();
				}
				$cartItem->setQuantity($quantity)
					->setBasePrice($quantity*$cartItem->getPrice() - $quantity*$cartItem->getDiscount())
					->save();

			}
			Mage::getSingleton('adminhtml/session')->addSuccess("Cartitems Updated");

		}catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index', ['id' => $customerId]);
	
	}

	public function deleteItemAction(){
		try{
			$customerId = $this->getRequest()->getParam('id');
			if(!$customerId){
				throw new Exception("Customer not exist");
			}
			$id = $this->getRequest()->getParam('itemid');
			if(!$id){
				Mage::getSingleton('adminhtml/session')->addError("Invalid id");	
			}
			$cartItem = Mage::getModel('order/order_cart_item')->load($id);
			if(!$cartItem->getId()){
				Mage::getSingleton('adminhtml/session')->addError("Item does not exist");	
			}
			if(!$cartItem->delete()){
				Mage::getSingleton('adminhtml/session')->addError("Unable to delete this item");	
			}
			Mage::getSingleton('adminhtml/session')->addSuccess("Item deleted");	

		}
		catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index', ['id' => $customerId]);
	}

	public function saveShippingAddressAction(){
		try{
			$customerId = $this->getRequest()->getParam('id');
			if(!$customerId){
				throw new Exception('Customer doesnot exist');
			}
			$data = $this->getRequest()->getPost('shipping');
			$cart = $this->_getCart();

			$cartAddress = $this->_getCart()->getShippingAddress();
			$addressBook = $this->getRequest()->getPost();

			if($addressBook['sameAsBilling']){
				$cartBillingAddress = $this->_getCart()->getBillingAddress();
				if(!$cartBillingAddress->getData()){
					throw new Exception("Save the cart billing address first");
				}
				
				$data = $cartBillingAddress->getData();
				unset($data['cart_address_id']);

				$cartAddress->addData($data)
					->setAddressType(Ccc_Order_Model_Order_Cart_Address::ADDRESS_TYPE_SHIPPING);

				if(!$cartAddress->save()){
					Mage::getSingleton('adminhtml/session')->addError("Unable to save shipping address of cart");
				}

				if($addressBook['saveShippingAddress']){
					$customerAddress = $this->_getCart()->getCustomer()->getShippingAddress();
					$customerAddress->addData($data)
						->setEntityTypeId(2)
						->setParentId($customerId);
					if(!$customerAddress->save()){
						Mage::getSingleton('adminhtml/session')->addError("Unable to save address of customer");
					}
				}
			} else{
				if($data['street']=='' || $data['city']=='' || $data['postcode']=='' || $data['region']=='' || $data['country']=='' )
				{
					throw new Exception('Please fill all the fields of shipping address');
				}
				$cartAddress->addData($data)
					->setCartId($cart->getId())
					->setAddressType(Ccc_Order_Model_Order_Cart_Address::ADDRESS_TYPE_SHIPPING);
				
				if(!$cartAddress->save()){
					Mage::getSingleton('adminhtml/session')->addError("Unable to save address of cart");	
				}
			
				if($addressBook['saveShippingAddress']){
					$customerAddress = $this->_getCart()->getCustomer()->getShippingAddress();
					$customerAddress->addData($data)
						->setEntityTypeId(2)
						->setParentId($customerId);
					if(!$customerAddress->save()){
						Mage::getSingleton('adminhtml/session')->addError("Unable to save address of customer");
					}
				}
			}
			Mage::getSingleton('adminhtml/session')->addSuccess("Shipping Address Saved Successfully");		
		}catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index', ['id' => $customerId]);
	}

	public function saveBillingAddressAction(){
		try{
			$customerId = $this->getRequest()->getParam('id');
			if(!$customerId){
				throw new Exception('Customer doesnot exist');
			}
			$data = $this->getRequest()->getPost('billing');
			$cart = $this->_getCart();

			$cartAddress = $this->_getCart()->getBillingAddress();
			
			$cartAddress->addData($data)
				->setCartId($cart->getId())
				->setAddressType(Ccc_Order_Model_Order_Cart_Address::ADDRESS_TYPE_BILLING);

			if(!$cartAddress->save()){
				Mage::getSingleton('adminhtml/session')->addError("Unable to save address of cart");	
			}
			$addressBook = $this->getRequest()->getPost();
			if($addressBook['saveBillingAddress']){
				$customerAddress = $this->_getCart()->getCustomer()->getBillingAddress();
				$customerAddress->addData($data)
					->setEntityTypeId(2)
					->setParentId($customerId);
				if(!$customerAddress->save()){
					Mage::getSingleton('adminhtml/session')->addError("Unable to save address of customer");
				}
			}
			Mage::getSingleton('adminhtml/session')->addSuccess("Billing Address Saved Successfully");
		}catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index', ['id' => $customerId]);
	}

	public function gridAction()
    {
        $this->getResponse()
            ->setBody($this->getLayout()->createBlock('order/adminhtml_order_cart_main_product_grid')->toHtml());
    }

	public function savePaymentMethodAction(){
		try{
			$customerId = $this->getRequest()->getParam('id');
			if(!$customerId){
				throw new Exception('Customer doesnot exist');
			}
			$paymentmethod = $this->getRequest()->getPost('paymentmethod');
			
			$cartModel = $this->_getCart();
			$cartModel->setPaymentMethodCode($paymentmethod);
			if(!$cartModel->save()){
				Mage::getSingleton('adminhtml/session')->addError("Unable to save");	
			}
			Mage::getSingleton('adminhtml/session')->addSuccess("Payment method saved");	

		}
		catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index', ['id' => $customerId]);
	}

	public function saveShippingMethodAction(){
		try{
			$customerId = $this->getRequest()->getParam('id');
			if(!$customerId){
				throw new Exception('Customer doesnot exist');
			}
			$shipmentData = $this->getRequest()->getPost('shippingmethod');
			$data = explode('_',$shipmentData);
			$cartModel = $this->_getCart();
			$cartModel->setShippingMethodCode($data[0]);
			$cartModel->setShippingAmount($data[1]);
			if(!$cartModel->save()){
				Mage::getSingleton('adminhtml/session')->addError("Unable to save in cart");	
			}
			Mage::getSingleton('adminhtml/session')->addSuccess("Shipment Method Saved");	

		}
		catch (Exception $e) {
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/index', ['id' => $customerId]);
	}

	public function newAction(){
		$this->loadLayout();
		$this->renderLayout();
	}

	public function saveCustomerAction(){
		try{
			$data = $this->getRequest()->getPost('order');
			if($data['account']['email'] = '' || $data['account']['group_id'] = '' || $data['billing_address']['firstname'] = '' || $data['billing_address']['lastname'] = '' || $data['billing_address']['street'] = '' || $data['billing_address']['city'] = '' || $data['billing_address']['country'] = '' || $data['billing_address']['region'] = '' || $data['billing_address']['postcode'] = '' || $data['billing_address']['telephone'] = '' || $data['shipping_address']['firstname'] = '' || $data['shipping_address']['lastname'] = '' || $data['shipping_address']['street'] = '' || $data['shipping_address']['city'] = '' || $data['shipping_address']['country'] = '' || $data['shipping_address']['region'] = '' || $data['shipping_address']['postcode'] = '' || $data['shipping_address']['telephone'] = ''){
				Mage::getSingleton('adminhtml/session')->addError("Fill all the required fields");	
			}
			$customerModel = Mage::getModel('customer/customer');
			$customerModel->addData($data['account']);
			if(!$customerModel->save()){
				Mage::getSingleton('adminhtml/session')->addError("Unable to save customer emailId and groupId");	
			}
			$customerModel->addData($data['billing_address']);
			if(!$customerModel->save()){
				Mage::getSingleton('adminhtml/session')->addError("Unable to save customer");	
			}

			
			$customerId = $customerModel->getId();
			
			if($data['billing_address']['save_in_address_book']){
				$customerAddress = $this->_getCart($customerId)->getCustomer()->getBillingAddress();
				$customerAddress->addData($data['billing_address'])
					->setEntityTypeId(2)
					->setParentId($customerId);
				if(!$customerAddress->save()){
					Mage::getSingleton('adminhtml/session')->addError("Unable to save billing address in customer");	
				}
			}	

			if($data['shipping_address']['save_in_address_book']){
				$customerAddress = $this->_getCart($customerId)->getCustomer()->getShippingAddress();
				$customerAddress->addData($data['shipping_address'])
					->setEntityTypeId(2)
					->setParentId($customerId);
				if(!$customerAddress->save()){
					Mage::getSingleton('adminhtml/session')->addError("Unable to save shipping address in customer");	
				}
			}
				
			date_default_timezone_set('Asia/Kolkata');
		
			$cart = Mage::getModel('order/order_cart');
			$cart->setCustomerId($customerId)
				->setCreatedAt(date('Y-m-d H:i:s'));
				
			if(!$cart->save()){
				Mage::getSingleton('adminhtml/session')->addError("Unable to save in cart");	
			}

			$cartBillingAddress = $cart->getBillingAddress();
			
			$cartBillingAddress->addData($data['billing_address'])
				->setCustomerId($customerId)
				->setCartId($cart->getId())
				->setAddressType(Ccc_Order_Model_Order_Cart_Address::ADDRESS_TYPE_BILLING);

			if(!$cartBillingAddress->save()){
				Mage::getSingleton('adminhtml/session')->addError("Unable to save billing address of cart");	
			}

			$cartShippingAddress = $cart->getShippingAddress();
			
			$cartShippingAddress->addData($data['shipping_address'])
				->setCustomerId($customerId)
				->setCartId($cart->getId())
				->setAddressType(Ccc_Order_Model_Order_Cart_Address::ADDRESS_TYPE_SHIPPING);

			if(!$cartShippingAddress->save()){
				Mage::getSingleton('adminhtml/session')->addError("Unable to save shipping address of cart");	
			}
			Mage::getSingleton('adminhtml/session')->addSuccess("Billing Address Saved Successfully");
		}catch (Exception $e) {
			$this->_redirect('vendor/adminhtml_order_cart/saveCustomer');
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
		}
		$this->_redirect('*/*/showcustomer');
	}
}

?>