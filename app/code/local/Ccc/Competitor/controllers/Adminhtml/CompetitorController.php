<?php
class Ccc_Competitor_Adminhtml_CompetitorController extends Mage_Adminhtml_Controller_Action{
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('competitor/competitor');
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('competitor');
        $this->_title('Competitor Grid');

        $this->_addContent($this->getLayout()->createBlock('competitor/adminhtml_competitor'));

        $this->renderLayout();
    }

    protected function _initCompetitor()
    {
        $this->_title($this->__('Competitor'))
            ->_title($this->__('Manage competitors'));

        $competitorId = (int) $this->getRequest()->getParam('id');
        $competitor   = Mage::getModel('competitor/competitor')
            ->setStoreId($this->getRequest()->getParam('store', 0))
            ->load($competitorId);

        Mage::register('current_competitor', $competitor);
        Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
        return $competitor;
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $competitorId = (int) $this->getRequest()->getParam('id');
        $competitor   = $this->_initCompetitor();

        if ($competitorId && !$competitor->getId()) {
            $this->_getSession()->addError(Mage::helper('catalog')->__('This competitor no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        $this->_title($competitor->getName());

        $this->loadLayout();

        $this->_setActiveMenu('competitor/competitor');

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->renderLayout();

    }

    public function saveAction()
    {

        try {

            $competitorData = $this->getRequest()->getPost('account');

            $competitor = Mage::getSingleton('competitor/competitor');

            if ($competitorId = $this->getRequest()->getParam('id')) {

                if (!$competitor->load($competitorId)) {
                    throw new Exception("No Row Found");
                }
                Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

            }
            $setId = $this->getRequest()->getParam('setId');
            $competitor->setAttributeSetId($setId);
            
            $competitor->addData($competitorData);

            $competitor->save();

            Mage::getSingleton('core/session')->addSuccess("competitor data added.");
            $this->_redirect('*/*/');

        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirect('*/*/');
        }

    }

    public function deleteAction()
    {
        try {
            $competitorModel = Mage::getModel('competitor/competitor');

            if (!($competitorId = (int) $this->getRequest()->getParam('id')))
                throw new Exception('Id not found');

            if (!$competitorModel->load($competitorId)) {
                throw new Exception('competitor does not exist');
            }

            if (!$competitorModel->delete()) {
                throw new Exception('Error in delete record', 1);
            }

            Mage::getSingleton('core/session')->addSuccess($this->__('The competitor has been deleted.'));

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }
        
        $this->_redirect('*/*/');
    }
}
?>