<?php 

class Ccc_Order_Adminhtml_Order_CartController extends Mage_Adminhtml_Controller_Action
{

	public function indexAction()
	{
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
		$productIds = $this->getRequest()->getParam('product');
		$session = Mage::getModel('order/session');
	
		$cart = $this->_getCart();

		foreach($productIds as $key => $productId){
			$product = Mage::getSingleton('catalog/product')->load($productId);

			$cart->addItemToCart($product,1,true);
		}
	}

	protected function _getCart(){
		
		$session = Mage::getModel('order/session');
		$cartId = $session->getCartId();

		$cart = Mage::getModel('order/order_cart');
		$cart->load($cartId);
		return $cart;
		
	}

	public function updateAction(){
		$data = $this->getRequest()->getPost();
		foreach($data['quantity'] as $cartItemId => $quantity){
			$cartItem = Mage::getModel('order/order_cart_item')->load($cartItemId);
			if($quantity == 0){
				$cartItem->delete();
			}
			$price = $cartItem->price;
			$cartItem->setQuantity($quantity)
				->setBasePrice($quantity*$price)
				->save();
		}
	}

	public function deleteAction(){
		$id = $this->getRequest()->getParam('id');
		$cartItem = Mage::getModel('order/order_cart_item')->load($id);
		$cartItem->delete();
	}

	public function saveBillingAddressAction(){
		$data = $this->getRequest()->getPost('billing');

		$customerId = Mage::getModel('order/session')->getCustomerId();
		$cartId = Mage::getModel('order/session')->getCartId();
		
		$cartAddress = Mage::getModel('order/order_cart_address');
		$customerAddress = Mage::getModel('customer/address')->load($customerId,'parent_id');
		
		$cartAddress->setCartId($cartId)
			->setAddressType('billing')
			->setStreet($data['street'])
			->setCity($data['city'])
			->setRegion($data['region'])
			->setCountryId($data['country_id'])
			->setPostcode($data['postcode'])
			->setAddressId($customerAddress->getId());
		$cartAddress->save();

		$addressBook = $this->getRequest()->getPost();
		if($addressBook['saveBillingAddress']){
			$customerAddress = Mage::getModel('customer/address')->load($customerId,'parent_id');
			$customerAddress
				->setStreet($data['street'])
				->setCity($data['city'])
				->setRegion($data['region'])
				->setCountryId($data['country_id'])
				->setPostcode($data['postcode'])
				->save();
		}

	}

	public function saveShippingAddressAction(){
		$data = $this->getRequest()->getPost('shipping');

		$customerId = Mage::getModel('order/session')->getCustomerId();
		$cartId = Mage::getModel('order/session')->getCartId();
		
		$cartAddress = Mage::getModel('order/order_cart_address');
		$customerAddress = Mage::getModel('customer/address')->load($customerId,'parent_id');
		
		$cartAddress->setCartId($cartId)
			->setAddressType('shipping')
			->setStreet($data['street'])
			->setCity($data['city'])
			->setRegion($data['region'])
			->setCountryId($data['country_id'])
			->setPostcode($data['postcode'])
			->setAddressId($customerAddress->getId());
		$cartAddress->save();

		$addressBook = $this->getRequest()->getPost();
		
		if($addressBook['sameAsBilling']){
			$cartAddress = Mage::getModel('order/order_cart_address')->load($cartId,'cart_id');

			$newCartAddress = Mage::getModel('order/order_cart_address');
			$newCartAddress
				->setAddressType('shipping')
				->setStreet($cartAddress->street)
				->setCity($cartAddress->city)
				->setRegion($cartAddress->region)
				->setCountryId($cartAddress->country_id)
				->setPostcode($cartAddress->postcode)
				->setAddressId($cartAddress->address_id);
			$newCartAddress->save();
		}
		
		if($addressBook['saveShippingAddress']){
			if($addressBook['sameAsBilling']){
				$customerAddress = Mage::getModel('customer/address')->load($customerId,'parent_id');
				$cartAddress = Mage::getModel('order/order_cart_address')->load($cartId,'cart_id');
				$customerAddress
					->setStreet($cartAddress->street)
					->setCity($cartAddress->city)
					->setRegion($cartAddress->region)
					->setCountryId($cartAddress->country_id)
					->setPostcode($cartAddress->postcode)
					->save();
			}
			else{
				$customerAddress = Mage::getModel('customer/address')->load($customerId,'parent_id');
				$customerAddress
					->setStreet($data['street'])
					->setCity($data['city'])
					->setRegion($data['region'])
					->setCountryId($data['country_id'])
					->setPostcode($data['postcode'])
					->save();
			}
			
		}
	}

	public function savePaymentMethodAction(){
		$data = $this->getRequest()->getPost();
		echo '<pre>';
		print_r($data);
		die();
	}
}

?>