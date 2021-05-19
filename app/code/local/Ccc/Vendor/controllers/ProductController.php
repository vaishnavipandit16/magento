<?php
class Ccc_Vendor_ProductController extends Mage_Core_Controller_Front_Action{

    protected $_resources;
    protected $_entityTypeId;
    
    public function newAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('vendor/account/login');
        }
        $this->_forward('edit');
    }

    protected function _getSession()
    {
        return Mage::getSingleton('vendor/session');
    }

    public function editAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('vendor/account/login');
        }
        $productId = (int) $this->getRequest()->getParam('id');
        $product = $this->_initProduct();

        if ($productId && !$product->getId()) {
            $this->_getSession()->addError(Mage::helper('vendor')->__('This product no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        $this->loadLayout();

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->renderLayout();
    }

    protected function _initProduct()
    {
        $this->_title($this->__('Catalog'))
            ->_title($this->__('Manage Products'));

        $productId = (int) $this->getRequest()->getParam('id');
        $product = Mage::getModel('vendor/product')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if (!$productId) {
            if ($setId = (int) $this->getRequest()->getParam('set')) {
                $product->setAttributeSetId($setId);
            }

            if ($typeId = $this->getRequest()->getParam('type')) {
                $product->setTypeId($typeId);
            }
        } else {
            $product->load($productId);
        }
        Mage::register('current_vendorproduct', $product);
        return $product;
    }

    public function saveproductAction(){
        if ($postData = $this->getRequest()->getPost()) {
            try {
                $postData = $this->getRequest()->getPost();
                
                $productId = $this->getRequest()->getParam('id');
                $vendorId = $this->_getSession()->getVendor()->getId();
                // $attributeSet = $this->getRequest()->getParam('set');
                // $productType = $this->getRequest()->getParam('type');

                $vendorProductModel = Mage::getModel('vendor/product')
                    ->setStoreId($this->getRequest()->getParam('store', 0))->load($productId);
                
                $sku = $postData['sku'];

                $isSku = Mage::getModel('vendor/product')->getResource()->getSkuById($sku);

                if(!$productId){
                    if($isSku){
                        throw new Exception("Product SKU already exists! (SKU must be unique.)");
                    }
                    $existsCatalogProduct = Mage::getModel('catalog/product')->getResource()->getIdBySku($sku);
                    if ($existsCatalogProduct) {
                        throw new Exception("Product SKU already exists!");
                    }
                }

                if ($vendorProductModel->getId()) {
                    $vendorProductModel->addData($postData);  
                } else {
                    $vendorProductModel->addData($postData);
                }
                $vendorProductModel->save();

                $entityId = $vendorProductModel->getId();
                
                $conn = Mage::getSingleton('core/resource')->getConnection('core_write');
                $sql = "UPDATE `vendor_product`set `vendor_id` = $vendorId,sku = '$sku' where `entity_id` = $entityId";
                $conn->query($sql);

                $requestModel = Mage::getModel('vendor/product_request');

                if($productId){
                    $requestModelCollection = Mage::getModel('vendor/product_request')->getCollection()
                        ->addFilter('product_id',$productId);
                    $request_id = $requestModelCollection->getData()[0]['request_id'];

                    $requestModel->setRequestId($request_id)
                        ->setVendorId($vendorId)
                        ->setProductId($productId)
                        ->setRequestType('edit')
                        ->setApproveStatus('pending')
                        ->setCreatedAt(date('Y-m-d H:i:s'));
                } else{
                    $requestModel->setVendorId($vendorId)
                        ->setProductId($vendorProductModel->getId())
                        ->setRequestType('insert')
                        ->setApproveStatus('pending')
                        ->setCreatedAt(date('Y-m-d H:i:s'));
                }
                $requestModel->save();

                $this->_getSession()->addSuccess(
                    $this->__('The Product has been saved.')
                );
                $this->_redirect('*/*/grid');
                return;
            } catch (Exception $e) {
                echo '<pre>';
                print_r($e);
                die();
                //$this->_redirect('vendor/product/new');
                Mage::getSingleton('vendor/session')->addError(Mage::helper('vendor')->__('An error occurred while saving this group.'));
            }
        }
        $this->_redirect('*/*/'); 
    }

    public function gridAction(){
        $this->loadLayout();
        $this->renderLayout();
    }

    public function deleteAction(){
        try {
            $vendorproductModel = Mage::getModel('vendor/product');
            $productId = (int) $this->getRequest()->getParam('id');
            // echo $productId;
            // die();
            if (!($productId = (int) $this->getRequest()->getParam('id')))
                throw new Exception('Id not found');

            if (!$vendorproductModel->load($productId)) {
                throw new Exception('vendor does not exist');
            }

            $requestModel = Mage::getModel('vendor/product_request');
            $vendorId = Mage::getSingleton('vendor/session')->getId();
           
            if($productId){
                $requestModelCollection = Mage::getModel('vendor/product_request')->getCollection()
                    ->addFilter('product_id',$productId);
                $request_id = $requestModelCollection->getData()[0]['request_id'];
                $requestModel->setRequestId($request_id)
                    ->setVendorId($vendorId)
                    ->setProductId($productId)
                    ->setRequestType('delete')
                    ->setApproveStatus('pending')
                    ->setCreatedAt(date('Y-m-d H:i:s'));
                    $requestModel->save();
            }

            Mage::getSingleton('core/session')->addSuccess($this->__('The vendor product has been deleted.'));

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/grid');
    }
 
}

?>