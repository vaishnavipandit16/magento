<?php
$installer = $this;
$installer->startSetup();

// $installer->addEntityType(Ccc_Competitor_Model_Resource_Competitor::ENTITY,[
//     'entity_model' => 'competitor/competitor',
//     'attribute_model' => 'competitor/attribute',
//     'table' => 'competitor/competitor',
//     'increment_per_strore' => 0,
//     'additional_attribute_table' => 'competitor/eav_attribute',
//     'entity_attribute_collection' => 'competitor/competitor_attribute_collection',

// ]);
// $installer->createEntityTables('competitor');
// $installer->createEntities();

// $default_attribute_set_id = Mage::getModel('eav/entity_setup','core_setup')
//                             ->getAttributeSetId('competitor','Default');
// $this->run(
//     "UPDATE `eav_entity_type` SET `default_attribute_set_id` = {$default_attribute_set_id}' WHERE `entity_type_code` = 'competitor'"
// );
$installer->endSetup();
?>