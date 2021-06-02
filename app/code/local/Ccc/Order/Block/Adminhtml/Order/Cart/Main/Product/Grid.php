<?php
class Ccc_Order_Block_Adminhtml_Order_Cart_Main_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid{

    public function __construct(){
        parent::__construct();
        $this->setId('order_product');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setSaveParametersInSession(true);
    }

    public function _prepareCollection(){
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->addAttributeToSelect('sku')
                ->addAttributeToSelect('name');
        if(Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')){
            $collection->joinField('qty',
                'cataloginventory/stock_item',
                'qty',
                'product_id = entity_id',
                '{{table}}.stock_id = 1',
                'left'
            );
        }

        $collection->joinAttribute(
            'name',
            'catalog_product/name',
            'entity_id',
            null,
            'inner'
        );
        
        $collection->joinAttribute(
            'custom_name',
            'catalog_product/name',
            'entity_id',
            null,
            'inner'
        );
        
        $collection->joinAttribute(
            'price',
            'catalog_product/price',
            'entity_id',
            null,
            'inner'
        );
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    public function _prepareColumns(){
        $this->addColumn('entity_id',
        array(
            'header' => Mage::helper('catalog')->__('Id'),
            'width' => '50px',
            'type' => 'number',
            'index' => 'entity_id'
        ));
        $this->addColumn('name',
        array(
            'header' => Mage::helper('catalog')->__('Name'),
            'index' => 'name'
        ));
        // $this->addColumn('type',
        // array(
        //     'header' => Mage::helper('catalog')->__('Type'),
        //     'width' => '50px',
        //     'type' => 'options',
        //     'index' => 'type_id'
        // ));
        $this->addColumn('sku',
        array(
            'header' => Mage::helper('catalog')->__('SKU'),
            'index' => 'sku'
        ));
        $this->addColumn('price',
        array(
            'header' => Mage::helper('catalog')->__('Price'),
            'index' => 'price'
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassAction(){
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('product');

        $this->getMassactionBlock()->addItem('status',array(
            'label' => Mage::helper('order')->__('Add to Cart'),
            'selected' => true,
            'url' => $this->getUrl('*/*/addToCart',array('_current' => true)),
        ));
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

}
?>