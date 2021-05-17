<?php

class Ccc_Vendor_Adminhtml_VendorproductController extends Mage_Adminhtml_Controller_Action
{
    protected $_resources;
    protected $_entityTypeId;

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('vendor/product');
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('vendor');
        $this->_title('Vendor product Grid');

        $this->_addContent($this->getLayout()->createBlock('vendor/adminhtml_vendorproduct'));

        $this->renderLayout();
    }

    public function preDispatch()
    {
        //$this->_setForcedFormKeyActions('delete');
        parent::preDispatch();
        $this->_entityTypeId = Mage::getModel('eav/entity')->setType(Ccc_Vendor_Model_Resource_Product::ENTITY)->getTypeId();
    }

    protected function _getSession()
    {
        return Mage::getSingleton('vendor/session');
    }

    protected function _initVendorproduct()
    {
        $this->_title($this->__('Product'))
            ->_title($this->__('Manage vendors'));

        $productId = (int) $this->getRequest()->getParam('id');
        // echo $productId;
        // die();
        $product   = Mage::getModel('vendor/product')
            ->setStoreId($this->getRequest()->getParam('store', 0));

            if (!$productId) {
                if ($setId = (int) $this->getRequest()->getParam('set')) {
                    $product->setAttributeSetId($setId);
                }
            } else {
                $product->load($productId);
            }

        // echo '<pre>';
        // print_r($product);
        // die();

        Mage::register('current_vendorproduct', $product);
        Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $product;
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $vendorproductId = (int) $this->getRequest()->getParam('id');
        $vendorproduct   = $this->_initVendorproduct();

        if ($vendorproductId && !$vendorproduct->getId()) {
            $this->_getSession()->addError(Mage::helper('vendor')->__('This vendor no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        $this->_title($vendorproduct->getName());

        $this->loadLayout();

        $this->_setActiveMenu('vendor');

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->renderLayout();

    }

    public function saveAction()
    {
        try {

            $vendorData = $this->getRequest()->getPost('account');

            $vendorproduct = Mage::getSingleton('vendor/product');

            if ($vendorproductId = $this->getRequest()->getParam('id')) {

                if (!$vendorproduct->load($vendorproductId)) {
                    throw new Exception("No Row Found");
                }
                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

            }

            $vendorproduct->addData($vendorData);

            $vendorproduct->save();

            Mage::getSingleton('core/session')->addSuccess("Product data added.");
            $this->_redirect('*/*/');

        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirect('*/*/');
        }

    }

    public function deleteAction()
    {
        try {
            $vendorproductModel = Mage::getModel('vendor/product');

            if (!($vendorproductId = (int) $this->getRequest()->getParam('id')))
                throw new Exception('Id not found');

            if (!$vendorproductModel->load($vendorproductId)) {
                throw new Exception('product does not exist');
            }

            if (!$vendorproductModel->delete()) {
                throw new Exception('Error in delete record', 1);
            }

            Mage::getSingleton('core/session')->addSuccess($this->__('The product has been deleted.'));

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/');
    }

    public function approveAction(){
        $vendorProductModel = Mage::getModel('vendor/product');
        $productId = $this->getRequest()->getParam('id');

        $default_attribute_set_id = Mage::getModel('eav/entity_setup','core_setup')
                ->getAttributeSetId('vendor_product','Default');

        $vendorProductData = $vendorProductModel->load($productId)->getData();
        $catalogProductModel = Mage::getModel('catalog/product');
        $requestModel = Mage::getModel('vendor/product_request');

        $requestModelCollection = Mage::getModel('vendor/product_request')->getCollection()
                ->addFilter('product_id',$productId);
        $requestId = $requestModelCollection->getData()[0]['request_id'];
        $requestType = $requestModelCollection->getData()[0]['request_type'];
        $catalogProductId = $requestModelCollection->getData()[0]['catalog_product_id'];

        try{
            if ($requestType == 'insert'){
                $vendorProductData['entity_id'] = null;
                $catalogProductModel->addData($vendorProductData)
                    ->setAttributeSetId($default_attribute_set_id)
                    ->setTypeId('simple')
                    ->setEntityTypeId($this->_entityTypeId);
                $catalogProductModel->save();

                $catalogProductId = $catalogProductModel->getId();
                $requestModel->setRequestId($requestId)
                    ->setCatalogProductId($catalogProductId)
                    ->setApproveStatus('approved')
                    ->setApprovedAt(date('Y-m-d H:i:s'));
                $requestModel->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('vendor')->__('product has been approved'));
                $this->_redirect('*/*/index');
                return;
            }

            if ($requestType == 'edit'){
                $catalogProductModel->load($catalogProductId)
                    ->addData($vendorProductData)
                    ->setAttributeSetId($default_attribute_set_id);
                $catalogProductModel->save();

                $requestModel->load($requestId)
                    ->setApproveStatus('approved')
                    ->setApprovedAt(date('Y-m-d H:i:s'));
                $requestModel->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('vendor')->__('product has been approved'));
                $this->_redirect('*/*/index');
                return;
            }

            if ($requestType == "delete"){
                if($vendorProductModel->load($productId)){
                    $vendorProductModel->delete();
                }
                // if($catalogProductModel->load($catalogProductId)){
                //     $catalogProductModel->delete();
                // }
                if($requestModel->load($requestId)){
                    $requestModel->setApproveStatus('approved')
                        ->setApprovedAt(date('Y-m-d H:i:s'))
                        ->save();
                }
                
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('vendor')->__('product has been deleted'));
                $this->_redirect('*/*/index');
                return;
            }
        }
        catch(Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return;
        }
    }

    public function rejectAction(){
        try{
            $productId = $this->getRequest()->getParam('id');
            $requestModel = Mage::getModel('vendor/product_request');

            $requestModelCollection = Mage::getModel('vendor/product_request')->getCollection()
                ->addFilter('product_id',$productId);

            $requestId = $requestModelCollection->getData()[0]['request_id'];

            $requestModel->setRequestId($requestId)
                ->setApproveStatus('rejected')
                ->setApprovedAt('Y-m-d H:i:s');
            $requestModel->save();

            Mage::getSingleton('adminhtml/session')->addSuccess('Request Rejected');
            return;
        }
        catch(Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return;
        }
    }

    
}
?>