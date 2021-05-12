<?php

class Ccc_Vendor_Block_Marketplace_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('productGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('product_filter');
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    public function getRequestStatus()
    {
        $vendorId = Mage::getModel('vendor/session')->getVendor()->getId();
        $collection = Mage::getModel('vendor/product_request')->getCollection()
        // echo '<pre>';
        // print_r($collection);
        // die();
            ->addFilter('vendor_id', $vendorId);
        return $collection;
    }

    public function getProducts()
    {
        $store = $this->_getStore();
        $collection = Mage::getModel('vendor/product')->getCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('attribute_set_id')
            ->addAttributeToSelect('type_id');
        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        // $collection->addStoreFilter($store);
        $collection->joinAttribute(
            'name',
            'vendor_product/name',
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
            $store->getId()
        );
        $collection->joinAttribute(
            'visibility',
            'vendor_product/visibility',
            'entity_id',
            null,
            'inner',
            $store->getId()
        );
        // $collection->joinAttribute(
        //     'price',
        //     'vendor_product/price',
        //     'entity_id',
        //     null,
        //     'left',
        //     $store->getId()
        // );


        $collection->addFilter('vendor_id', ['eq' => Mage::getSingleton('vendor/session')->getVendor()->getId()]);
        // echo '<pre>';
        // print_r($collection->getData());
        // die();
        return $collection;
    }

    protected function _addColumnFilterToCollection($column)
    {
        if ($this->getCollection()) {
            if ($column->getId() == 'websites') {
                $this->getCollection()->joinField(
                    'websites',
                    'catalog/product_website',
                    'website_id',
                    'product_id=entity_id',
                    null,
                    'left'
                );
            }
        }
        return parent::_addColumnFilterToCollection($column);
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('catalog')->__('ID'),
                'width' => '50px',
                'type'  => 'number',
                'index' => 'entity_id',
            )
        );
        // $this->addColumn('name',
        //     array(
        //         'header'=> Mage::helper('catalog')->__('Name'),
        //         'index' => 'name',
        // ));

        $store = $this->_getStore();
        if ($store->getId()) {
            $this->addColumn(
                'custom_name',
                array(
                    'header' => Mage::helper('catalog')->__('Name in %s', $store->getName()),
                    'index' => 'custom_name',
                )
            );
        }

        $this->addColumn(
            'type',
            array(
                'header' => Mage::helper('catalog')->__('Type'),
                'width' => '60px',
                'index' => 'type_id',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_type')->getOptionArray(),
            )
        );

        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->load()
            ->toOptionHash();

        $this->addColumn(
            'set_name',
            array(
                'header' => Mage::helper('catalog')->__('Attrib. Set Name'),
                'width' => '100px',
                'index' => 'attribute_set_id',
                'type'  => 'options',
                'options' => $sets,
            )
        );

        $this->addColumn(
            'sku',
            array(
                'header' => Mage::helper('catalog')->__('SKU'),
                'width' => '80px',
                'index' => 'sku',
            )
        );

        $store = $this->_getStore();
        // $this->addColumn(
        //     'price',
        //     array(
        //         'header' => Mage::helper('catalog')->__('Price'),
        //         'type'  => 'price',
        //         'currency_code' => $store->getBaseCurrency()->getCode(),
        //         'index' => 'price',
        //     )
        // );

        // if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
        //     $this->addColumn('qty',
        //         array(
        //             'header'=> Mage::helper('catalog')->__('Qty'),
        //             'width' => '100px',
        //             'type'  => 'number',
        //             'index' => 'qty',
        //     ));
        // }

        $this->addColumn(
            'visibility',
            array(
                'header' => Mage::helper('catalog')->__('Visibility'),
                'width' => '70px',
                'index' => 'visibility',
                'type'  => 'options',
                'options' => Mage::getModel('catalog/product_visibility')->getOptionArray(),
            )
        );

        $this->addColumn(
            'status',
            array(
                'header' => Mage::helper('catalog')->__('Status'),
                'width' => '70px',
                'index' => 'status',
                'type'  => 'options',
                'options' => Mage::getSingleton('catalog/product_status')->getOptionArray(),
            )
        );

        // if (!Mage::app()->isSingleStoreMode()) {
        //     $this->addColumn('websites',
        //         array(
        //             'header'=> Mage::helper('catalog')->__('Websites'),
        //             'width' => '100px',
        //             'sortable'  => false,
        //             'index'     => 'websites',
        //             'type'      => 'options',
        //             'options'   => Mage::getModel('core/website')->getCollection()->toOptionHash(),
        //     ));
        // }

        $this->addColumn(
            'action',
            array(
                'header'    => Mage::helper('catalog')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('catalog')->__('Edit'),
                        'url'     => array(
                            'base' => '*/*/edit',
                            'params' => array('store' => $this->getRequest()->getParam('store'))
                        ),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
            )
        );

        if (Mage::helper('catalog')->isModuleEnabled('Mage_Rss')) {
            $this->addRssList('rss/catalog/notifystock', Mage::helper('catalog')->__('Notify Low Stock RSS'));
        }

        return parent::_prepareColumns();
    }

    // protected function _prepareMassaction()
    // {
    //     $this->setMassactionIdField('entity_id');
    //     $this->getMassactionBlock()->setFormFieldName('product');

    //     $this->getMassactionBlock()->addItem('delete', array(
    //          'label'=> Mage::helper('catalog')->__('Delete'),
    //          'url'  => $this->getUrl('*/*/massDelete'),
    //          'confirm' => Mage::helper('catalog')->__('Are you sure?')
    //     ));

    //     $statuses = Mage::getSingleton('catalog/product_status')->getOptionArray();

    //     array_unshift($statuses, array('label'=>'', 'value'=>''));
    //     $this->getMassactionBlock()->addItem('status', array(
    //          'label'=> Mage::helper('catalog')->__('Change status'),
    //          'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
    //          'additional' => array(
    //                 'visibility' => array(
    //                      'name' => 'status',
    //                      'type' => 'select',
    //                      'class' => 'required-entry',
    //                      'label' => Mage::helper('catalog')->__('Status'),
    //                      'values' => $statuses
    //                  )
    //          )
    //     ));

    //     if (Mage::getSingleton('admin/session')->isAllowed('catalog/update_attributes')){
    //         $this->getMassactionBlock()->addItem('attributes', array(
    //             'label' => Mage::helper('catalog')->__('Update Attributes'),
    //             'url'   => $this->getUrl('*/catalog_product_action_attribute/edit', array('_current'=>true))
    //         ));
    //     }

    //     Mage::dispatchEvent('adminhtml_catalog_product_grid_prepare_massaction', array('block' => $this));
    //     return $this;
    // }

    public function getGridUrl()
    {
        return $this->getUrl('*/product/grid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            array(
                'store' => $this->getRequest()->getParam('store'),
                'id' => $row->getId()
            )
        );
    }
    public function getAddUrl()
    {
        return $this->getUrl('*/product/new');
    }
    public function getPagetHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function editUrl(){
        return $this->getUrl('*/product/new');
    }
}