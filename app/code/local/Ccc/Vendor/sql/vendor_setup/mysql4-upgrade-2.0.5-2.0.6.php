<?php 
$installer = $this;
$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('vendor/product'), 'sku','varchar(12) default NULL');
$installer->endSetup();
?>