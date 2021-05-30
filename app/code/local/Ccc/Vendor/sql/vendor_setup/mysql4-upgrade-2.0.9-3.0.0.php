<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('vendor_product')}
    CHANGE `vendor_Id` `vendor_id` int(10);
");

$installer->endSetup();
?>