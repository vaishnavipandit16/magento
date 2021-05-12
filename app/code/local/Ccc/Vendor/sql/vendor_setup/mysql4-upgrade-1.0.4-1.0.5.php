<?php
$installer = $this;

$installer->getConnection()->addColumn($installer->getTable('vendor/eav_attribute'), 'store
_id','int(10) default NULL');

$installer->endSetup();

?>