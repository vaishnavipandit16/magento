<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()->dropColumn($this->getTable('vendor/eav_attribute'), 'store_id');

$installer->endSetup();
?>