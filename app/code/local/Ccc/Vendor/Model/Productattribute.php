<?php

class Ccc_Vendor_Model_Productattribute extends Mage_Eav_Model_Attribute
{
    const MODULE_NAME = 'Ccc_Vendor';

    const SCOPE_STORE                           = 0;
    const SCOPE_GLOBAL                          = 1;
    const SCOPE_WEBSITE                         = 2;

    protected $_eventObject = 'productattribute';

    protected function _construct()
    {
        $this->_init('vendor/productattribute');
    }

    public function isScopeStore()
    {
        return !$this->isScopeGlobal() && !$this->isScopeWebsite();
    }
    public function isScopeGlobal()
    {
        return $this->getIsGlobal() == self::SCOPE_GLOBAL;
    }

    public function isScopeWebsite()
    {
        return $this->getIsGlobal() == self::SCOPE_WEBSITE;
    }
}