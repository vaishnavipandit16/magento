<?php
$installer = $this;
$installer->startSetup();

$installer->run(
    "DROP TABLE IF EXISTS `{$installer->getTable('vendor_int')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_varchar')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_datetime')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_decimal')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_text')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_char')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_product_int')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_product_varchar')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_product_char')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_product_text')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_product_decimal')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_product_datetime')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_product')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_eav_attribute')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_eav_attribute_website')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_form_attribute')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_product_attribute_group')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_product_eav_attribute')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('vendor_product_request')}`;
    ALTER TABLE sales_flat_order_item DROP COLUMN IF EXISTS `vendor_id`;
    DELETE FROM `eav_entity_type` WHERE `entity_type_code` IN('vendor','vendor_product');
    DELETE FROM `core_resource` WHERE `code` = 'vendor_setup';"
);

$installer->addEntityType(Ccc_Vendor_Model_Resource_Vendor::ENTITY, [
    'entity_model'                => 'vendor/vendor',
    'attribute_model'             => 'vendor/attribute',
    'table'                       => 'vendor/vendor',
    'increment_per_store'         => '0',
    'additional_attribute_table'  => 'vendor/eav_attribute',
    'entity_attribute_collection' => 'vendor/vendor_attribute_collection',
]);

$installer->createEntityTables('vendor');
// $this->installEntities();

$default_attribute_set_id = Mage::getModel('eav/entity_setup', 'core_setup')
    						->getAttributeSetId('vendor', 'Default');

$installer->run("UPDATE `eav_entity_type` SET `default_attribute_set_id` = {$default_attribute_set_id} WHERE `entity_type_code` = 'vendor'");

$installer->endSetup();