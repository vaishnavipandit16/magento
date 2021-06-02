<?php
class Ccc_Competitor_Model_Attribute extends Mage_Eav_Model_Attribute{

    const MODULE_NAME = 'Ccc_Competitor';

    protected $_eventObject = 'attribute';

    public function _construct(){
        $this->_init('competitor/attribute');
    }
}
?>