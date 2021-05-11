<?php
class Ccc_Competitor_Model_Resource_Competitor extends Mage_Eav_Model_Entity_Abstract{

    const ENTITY = 'competitor';
    public function __construct()
    {
        parent::__construct();
        $this->setType(self::ENTITY)
             ->setConnection('core_read', 'core_write');
             
    }
}
?>