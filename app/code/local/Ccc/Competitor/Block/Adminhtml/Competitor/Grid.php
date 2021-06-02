<?php

class Ccc_Competitor_Block_Adminhtml_Competitor_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('competitorGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        //$this->setUseAjax(true);
        //$this->setVarNameFilter('competitor_filter');

    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $store = $this->_getStore();

        $collection = Mage::getModel('competitor/competitor')->getCollection();
            // ->addAttributeToSelect('firstname')
            // ->addAttributeToSelect('lastname');

        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $collection->joinAttribute(
            'firstname',
            'competitor/firstname',
            'entity_id',
            null,
            'inner',
            $adminStore
        );

        $collection->joinAttribute(
            'lastname',
            'competitor/lastname',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        
        $collection->joinAttribute(
            'id',
            'competitor/entity_id',
            'entity_id',
            null,
            'inner',
            $adminStore
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header' => Mage::helper('competitor')->__('id'),
                'width'  => '50px',
                'index'  => 'id',
                'type' => 'number',
            ));
        $this->addColumn('firstname',
            array(
                'header' => Mage::helper('competitor')->__('First Name'),
                'width'  => '50px',
                'index'  => 'firstname',
                'type' => 'text'
            ));

        $this->addColumn('lastname',
            array(
                'header' => Mage::helper('competitor')->__('Last Name'),
                'width'  => '50px',
                'index'  => 'lastname',
            ));

    
        $this->addColumn('action',
            array(
                'header'   => Mage::helper('competitor')->__('Action'),
                'width'    => '50px',
                'type'     => 'action',
                'getter'   => 'getId',
                'actions'  => array(
                    array(
                        'caption' => Mage::helper('competitor')->__('Delete'),
                        'url'     => array(
                            'base' => '*/*/delete',
                        ),
                        'field'   => 'id',
                    ),
                ),
                'filter'   => false,
                'sortable' => false,
            ));

        return parent::_prepareColumns();
        // return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/index', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'store' => $this->getRequest()->getParam('store'),
            'id'    => $row->getId())
        );
    }
}