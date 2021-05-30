<?php

class Ccc_Order_Block_Adminhtml_Order_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('custom_order_grid');
        $this->setSaveParametersInSession(true);
    }

    protected function _getCollectionClass()
    {
        return 'order/order_collection';
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('order_id', array(
            'header'=> Mage::helper('sales')->__('Order #'),
            'width' => '80px',
            'type'  => 'text',
            'index' => 'order_id',
        ));
        
        $this->addColumn('customer_name', array(
            'header' => Mage::helper('sales')->__('Customer Name'),
            'index' => 'customer_name',
        ));

        $this->addColumn('created_at', array(
            'header' => Mage::helper('sales')->__('Purchased On'),
            'index' => 'created_at',
            'type' => 'datetime',
            'width' => '100px',
        ));


        $this->addColumn('discount', array(
            'header' => Mage::helper('sales')->__('Discount'),
            'index' => 'discount',
        ));

        $this->addColumn('total', array(
            'header' => Mage::helper('sales')->__('Grand Total'),
            'index' => 'total',
            'type'  => 'currency',
            'currency' => 'order_currency_code',
        ));
        /*$this->addColumn('status', array(
            'header' => Mage::helper('sales')->__('Status'),
            'index' => 'status',
            'type'  => 'options',
            'width' => '70px',
            'options' => Mage::getSingleton('sales/order_config')->getStatuses(),
        ));*/
        $this->addRssList('rss/order/new', Mage::helper('sales')->__('New Order RSS'));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel XML'));
        return parent::_prepareColumns();
    }

    
    public function getRowUrl($row)
    {
        return $this->getUrl('*/adminhtml_order/view', array('id' => $row->getId()));
    }

    // public function getGridUrl()
    // {
    //     return $this->getUrl('*/*/grid', array('_current'=>true));
    // }

}