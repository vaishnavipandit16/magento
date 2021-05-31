<?php 
class Ccc_Order_Model_Order_Cart extends Mage_Core_Model_Abstract
{

	protected $_customer;

	function _construct()
	{
		$this->_init('order/order_cart');
	}

	public function addItemToCart($product,$quantity,$addMode = false){
		$cartId = Mage::getModel('order/session')->getCartId();
		$productId = $product->entity_id;

		$collection = Mage::getModel('order/order_cart_item')->getCollection();
		// $collection->getSelect()
		// 	->where('cart_id=?',$cartId)
		// 	->andWhere('product_id=?',$productId);
		$collection->addFieldToFilter('cart_id', ['eq' => $cartId]);
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
        $cartItem->cartId = $cartId;
        $cartItem->productId = $product->entity_id;
        $cartItem->price = $product->price;
        $cartItem->quantity = $quantity;
        $cartItem->discount = $product->discount;
        $cartItem->basePrice = $product->price - $product->discount;
        $cartItem->createdDate = date('Y-m-d H:i:s');
        $cartItem->save();
        return true;
    }
}

?>