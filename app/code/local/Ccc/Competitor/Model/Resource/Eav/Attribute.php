<?php
class Ccc_Competitor_Model_Resource_Eav_Attribute extends Mage_Eav_Model_Entity_Attribute{
    const SCOPE_STORE                           = 0;
    const SCOPE_GLOBAL                          = 1;
    const SCOPE_WEBSITE                         = 2;

    const MODULE_NAME                           = 'Mage_Competitor';
    const ENTITY                                = 'competitor_eav_attribute';

    protected function _construct()
    {
        $this->_init('competitor/attribute');
    }

    public function getIsGlobal()
    {
        return $this->_getData('is_global');
    }

    public function isScopeGlobal()
    {
        return $this->getIsGlobal() == self::SCOPE_GLOBAL;
    }

    public function isScopeWebsite()
    {
        return $this->getIsGlobal() == self::SCOPE_WEBSITE;
    }

    public function isScopeStore()
    {
        return !$this->isScopeGlobal() && !$this->isScopeWebsite();
    }
}
?>