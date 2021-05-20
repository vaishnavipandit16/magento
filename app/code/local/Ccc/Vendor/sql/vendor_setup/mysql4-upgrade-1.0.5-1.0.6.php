<?php
$installer = $this;

$installer->getConnection()->dropColumn($this->getTable('vendor/eav_attribute'), 'sort_id');

$installer->getConnection()->addColumn($installer->getTable('vendor/eav_attribute'), 'sort_order','int(10) default NULL');

$installer->endSetup();

?>