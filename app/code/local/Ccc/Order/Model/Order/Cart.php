<?php 
class Ccc_Order_Model_Order_Cart extends Mage_Core_Model_Abstract
{

    protected $customer = null;
    protected $cartitems = null;
    protected $billingAddress = null;

    function _construct()
    {
      $this->_init('order/order_cart');
    }

	  public function setCustomer(Mage_Customer_Model_Customer $customer)
    {
      $this->customer = $customer;
      return $this;
    }

    public function getCustomer()
    {
      if($this->customer){
        return $this->customer;
      }
      $customer = Mage::getModel('customer/customer')->load($this->customer_id);
      $this->setCustomer($customer);
      return $this->customer;
    }

    public function setCartItems(Ccc_Order_Model_Resource_Order_Cart_Item_Collection $items)
    {
      $this->cartitems = $items;
      return $this;
    }

    public function getCartItems()
    { 
      if($this->cartitems){
        return $this->cartitems;
      }
      $cartitems = Mage::getModel('order/order_cart_item')->getCollection();
      $cartitems->addFieldToFilter('cart_id',['eq' => $this->cart_id]);
      $this->setCartItems($cartitems);
      return $this->cartitems; 
    }

    public function addItemToCart($product,$quantity,$addMode = false)
    {
      $productId = $product->entity_id;
      $collection = Mage::getModel('order/order_cart_item')->getCollection();
      $collection->addFieldToFilter('cart_id', ['eq' => $this->cartId]);
      $collection->addFieldToFilter('product_id', ['eq' => $productId]);

      $id = $collection->getData()[0]['cart_item_id'];
      
      $cartItem = Mage::getModel('order/order_cart_item');
      $data = $cartItem->load($id);
     
      if($data->getData()){
        $data->quantity += $quantity;
        $data->basePrice =($product->price*$data->quantity)-($data->quantity*$product->discount);
        $cartItem->save();
        return true;
      }
      date_default_timezone_set('Asia/Kolkata');
      $cartItem->cartId = $this->cartId;
      $cartItem->productId = $product->entity_id;
      $cartItem->price = $product->price;
      $cartItem->quantity = $quantity;
      $cartItem->discount = $product->discount;
      $cartItem->basePrice = $product->price - $product->discount;
      $cartItem->name = $product->name;
      $cartItem->createdDate = date('Y-m-d H:i:s');
      $cartItem->save();
      return true;
    }
    
    public function setBillingAddress(Ccc_Order_Model_Order_Cart_Address $billingAddress){
        $this->billingAddress = $billingAddress;
        return $this;
    }
    
    public function getBillingAddress()
    {
      if($this->billingAddress){
        return $this->billingAddress;
      }
      if(!$this->cartId){
        return false;
      }
      $address= Mage::getModel('order/order_cart_address');
      $addressCollection= $address->getCollection()
              ->addFieldToFilter('cart_id',['eq'=>$this->cart_id])
              ->addFieldToFilter('address_type',['eq'=>Ccc_Order_Model_Order_Cart_Address::ADDRESS_TYPE_BILLING]);
      
      $id = $addressCollection->getData()[0]['cart_address_id'];
      $address = $address->load($id);
      return $address;
    }

    public function setShippingAddress(Ccc_Order_Model_Order_Cart_Address $shippingAddress){
      $this->shippingAddress = $shippingAddress;
      return $this;
    }
  
    public function getShippingAddress()
    {
      if($this->shippingAddress){
        return $this->shippingAddress;
      }
      if(!$this->cartId){
        return false;
      }
      $address= Mage::getModel('order/order_cart_address');
      $addressCollection= $address->getCollection()
              ->addFieldToFilter('cart_id',['eq'=>$this->cart_id])
              ->addFieldToFilter('address_type',['eq'=>Ccc_Order_Model_Order_Cart_Address::ADDRESS_TYPE_SHIPPING]);
      
      $id = $addressCollection->getData()[0]['cart_address_id'];
      $address = $address->load($id);
      return $address;
    }
}

?>