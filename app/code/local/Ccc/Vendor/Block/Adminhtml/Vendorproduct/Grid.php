<?php

class Ccc_Vendor_Block_Adminhtml_Vendorproduct_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('vendorGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('vendor_filter');

    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        //$collection = Mage::getModel('vendor/product_request')->getResourceCollection();
        // $collection->getSelect()->join(
        //     array('product' => 'vendor_product'),
        //     'product.entity_id = main_table.product_id' ,
        //     array('*')
        // );

        // $collection->addFilter('approve_status','pending');
        
        $store = $this->_getStore();

        $collection = Mage::getModel('vendor/product')->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('weight')
             ->addAttributeToSelect('status')
             ->addAttributeToSelect('sku');

        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $collection->joinAttribute(
            'name',
            'vendor_product/name',
            'entity_id',
            null,
            'inner',
            $adminStore
        );

        $collection->joinAttribute(
            'sku',
            'vendor_product/sku',
            'entity_id',
            null,
            'inner',
            $adminStore
        );

        $collection->joinAttribute(
            'weight',
            'vendor_product/weight',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $collection->joinAttribute(
            'status',
            'vendor_product/status',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        // $collection->joinAttribute(
        //     'phoneNo',
        //     'vendor_product/phoneNo',
        //     'entity_id',
        //     null,
        //     'inner',
        //     $adminStore
        // );
        $collection->joinAttribute(
            'id',
            'vendor_product/entity_id',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        
        $requestCollection = Mage::getModel('vendor/product_request')->getCollection();
        $requestCollection->addFieldToFilter('request_type',array('in'=>array('edit','insert')));
        $varienCollection = new Varien_Data_Collection();
        foreach ($requestCollection->getData() as $request){
            foreach ($collection->getData() as $products){
                if ($request['product_id'] == $products['entity_id']){
                    $finalArray = array_merge($request, $products);
                }
            }
            $rowObj = new Varien_Object();
            $rowObj->setData($finalArray); 
            $varienCollection->addItem($rowObj);
        }
        $this->setCollection($varienCollection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header' => Mage::helper('vendor')->__('id'),
                'width'  => '50px',
                'index'  => 'id',
            ));
        $this->addColumn('vendor_id',
            array(
                'header' => Mage::helper('vendor')->__('Vendor_Id'),
                'width'  => '50px',
                'index'  => 'vendor_Id',
            ));
        $this->addColumn('Name',
            array(
                'header' => Mage::helper('vendor')->__('Name'),
                'width'  => '50px',
                'index'  => 'name',
            ));

        $this->addColumn('Weight',
            array(
                'header' => Mage::helper('vendor')->__('weight'),
                'width'  => '50px',
                'index'  => 'weight',
            ));

        $this->addColumn('status',
            array(
                'header' => Mage::helper('vendor')->__('Status'),
                'width'  => '50px',
                'index'  => 'status',
            ));
        $this->addColumn('sku',
            array(
                'header' => Mage::helper('vendor')->__('SKU'),
                'width'  => '50px',
                'index'  => 'sku',
            ));
        $this->addColumn('request_type',
            array(
                'header' => Mage::helper('vendor')->__('Request_Type'),
                'width'  => '50px',
                'index'  => 'request_type',
            ));
        $this->addColumn('status',
            array(
                'header' => Mage::helper('vendor')->__('Status'),
                'width'  => '50px',
                'index'  => 'status',
            ));

        // $this->addColumn('phoneNo',
        //     array(
        //         'header' => Mage::helper('vendor')->__('Phone Number'),
        //         'width'  => '50px',
        //         'index'  => 'phoneNo',
        //     ));

        $this->addColumn(
            'action',
            array(
                'header'   => Mage::helper('vendor')->__('Action'),
                'width'    => '50px',
                'type'     => 'action',
                'getter'   => 'getId',
                'actions'  => array(
                    array(
                        'caption' => Mage::helper('vendor')->__('APPROVE'),
                        'url'     => array(
                            'base' => '*/*/approve',
                        ),
                        'field'   => 'id',
                    ),
                    array(
                        'caption' => Mage::helper('vendor')->__('REJECT'),
                        'url'     => array(
                            'base' => '*/*/reject',
                        ),
                        'field'   => 'id',
                    ),

                ),
                'filter'   => false,
                'sortable' => false,
            )
        );
        parent::_prepareColumns();
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'store' => $this->getRequest()->getParam('store'),
            'id'    => $row->getId())
        );
    }
}