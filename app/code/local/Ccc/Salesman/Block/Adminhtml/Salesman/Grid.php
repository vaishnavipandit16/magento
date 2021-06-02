<?php
class Ccc_Salesman_Block_Adminhtml_Salesman_Grid extends Mage_Adminhtml_Block_Widget_Grid{
    public function __construct(){
        parent::__construct();
        $this->setId('salesmanGrid');
        $this->setDefaultSort('salesman_id');
        $this->setDefaultDir('ASC');
        $this->setParametersInSession(true);
    }

    protected function _prepareCollection(){
        $collection = Mage::getModel('ccc_salesman/data')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns(){
        $this->addColumn('salesman_id',array(
            'header' => Mage::helper('salesman')->__('ID Number'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'salesman_id',
            'type' => 'number'
        ));
        $this->addColumn('code',array(
            'header' => Mage::helper('salesman')->__('Code'),
            'align' => 'left',
            'index' => 'code'
        ));
        $this->addColumn('name',array(
            'header' => Mage::helper('salesman')->__('Name'),
            'align' => 'left',
            'index' => 'name'
        ));
        $this->addColumn('email',array(
            'header' => Mage::helper('salesman')->__('Email'),
            'align' => 'left',
            'index' => 'email'
        ));
        $this->addColumn('mobile',array(
            'header' => Mage::helper('salesman')->__('Mobile'),
            'align' => 'left',
            'index' => 'mobile'
        ));
        $this->addColumn('created_date',array(
            'header' => Mage::helper('salesman')->__('Created Date'),
            'align' => 'left',
            'index' => 'created_date',
            'type' => 'datetime'
        ));
        return parent::_prepareColumns();
    }

    public function getRowUrl($row){
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
?>