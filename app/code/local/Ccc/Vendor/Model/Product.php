<?php

class Ccc_Vendor_Model_Product extends Mage_Core_Model_Abstract
{
    const ENTITY = 'vendor_product';
    protected function _construct()
    {
        parent::_construct();
        $this->_init('vendor/product');

    }

    protected $_attributes;

    public function getAttributes()
    {

        if ($this->_attributes === null) {
            $this->_attributes = $this->_getResource()
                ->loadAllAttributes($this)
                ->getSortedAttributes();
        }
        return $this->_attributes;
    }

    public function checkInGroup($attributeId, $setId, $groupId)
    {
        $resource = Mage::getSingleton('core/resource');

        $readConnection = $resource->getConnection('core_read');
        //$readConnection = $resource->getConnection('core_read');

        $query = '
            SELECT * FROM ' .
        $resource->getTableName('eav/entity_attribute')
            . ' WHERE `attribute_id` =' . $attributeId
            . ' AND `attribute_group_id` =' . $groupId
            . ' AND `attribute_set_id` =' . $setId
        ;

        $results = $readConnection->fetchRow($query);

        if ($results) {
            return true;
        }
        return false;
    }

    public function isLockedAttribute($attributeCode)
    {
        return isset($this->_lockedAttributes[$attributeCode]);
    }

    public function getMediaAttributes()
    {
        if (!$this->hasMediaAttributes()) {
            $mediaAttributes = array();
            foreach ($this->getAttributes() as $attribute) {
                if($attribute->getFrontend()->getInputType() == 'media_image') {
                    $mediaAttributes[$attribute->getAttributeCode()] = $attribute;
                }
            }
            $this->setMediaAttributes($mediaAttributes);
        }
        return $this->getData('media_attributes');
    }

    public function formatUrlKey($str)
    {
        return $this->getUrlModel()->formatUrlKey($str);
    }

    public function getUrlModel()
    {
        if ($this->_urlModel === null) {
            $this->_urlModel = Mage::getSingleton('catalog/factory')->getProductUrlInstance();
        }
        return $this->_urlModel;
    }
}