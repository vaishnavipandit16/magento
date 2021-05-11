<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('vendor')}
    CHANGE `store_id` `store_id` smallint(5) unsigned NULL DEFAULT '0';
");
// $installer->run("
// ALTER TABLE {$this->getTable('vendor')}
//     ADD INDEX `IDX_ENTITY_TYPE` (`entity_type_id`);
// ");

// $installer->run("
// ALTER TABLE {$this->getTable('vendor')}
//     ADD COLUMN `website_id` SMALLINT(5) UNSIGNED AFTER `attribute_set_id`,
//     ADD COLUMN `email` VARCHAR(255) NOT NULL AFTER `website_id`,
//     ADD COLUMN `group_id` SMALLINT(3) UNSIGNED NOT NULL AFTER `email`,
//     ADD INDEX IDX_AUTH(`email`, `website_id`),
//     ADD CONSTRAINT `FK_VENDOR_WEBSITE` FOREIGN KEY `FK_CUSTOMER_WEBSITE` (`website_id`)
//         REFERENCES {$this->getTable('core_website')} (`website_id`) ON DELETE SET NULL ON UPDATE CASCADE;
// ");

$emailAttributeId = $installer->getAttributeId('vendor', 'email');
$groupAttributeId = $installer->getAttributeId('vendor', 'group_id');

$installer->run("
    UPDATE {$this->getTable('vendor')} vendor, {$this->getTable('core_store')} store
    SET vendor.website_id=store.website_id
    WHERE store.store_id=vendor.store_id;

    UPDATE {$this->getTable('vendor')} vendor, {$this->getTable('vendor_varchar')} varchar_attribute
    SET vendor.email=varchar_attribute.value
    WHERE varchar_attribute.entity_id=vendor.entity_id
    AND varchar_attribute.attribute_id='{$emailAttributeId}';

    UPDATE {$this->getTable('vendor')} vendor, {$this->getTable('vendor_int')} int_attribute
    SET vendor.group_id=int_attribute.value
    WHERE int_attribute.entity_id=vendor.entity_id
    AND int_attribute.attribute_id='{$groupAttributeId}';
");

$installer->installEntities();
$installer->endSetup();

?>