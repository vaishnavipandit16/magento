<?php 
$installer = $this;
$installer->startSetup();
$installer->getConnection()->addColumn($installer->getTable('vendor/product'), 'vendor_Id','int(10) default NULL');
$installer->endSetup();
?>