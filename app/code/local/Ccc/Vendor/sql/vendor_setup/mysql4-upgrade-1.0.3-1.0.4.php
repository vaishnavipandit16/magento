<?php
$installer = $this;

$installer->run("
CREATE TABLE `{$installer->getTable('vendor/form_attribute')}` (
  `form_code` char(32) NOT NULL,
  `attribute_id` smallint UNSIGNED NOT NULL,
  PRIMARY KEY(`form_code`, `attribute_id`),
  KEY `IDX_VENDOR_FORM_ATTRIBUTE_ATTRIBUTE` (`attribute_id`),
  CONSTRAINT `FK_VENDOR_FORM_ATTRIBUTE_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES `{$installer->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Vendor attributes/forms relations';
");
?>