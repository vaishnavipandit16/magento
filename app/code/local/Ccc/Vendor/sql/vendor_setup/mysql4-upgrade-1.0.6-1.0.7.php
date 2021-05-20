<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()->dropColumn($this->getTable('vendor/eav_attribute'), 'store
_id');

$installer->endSetup();
?>