<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('order_item')}
    ADD  COLUMN `base_price` decimal(10,0);
");

$installer->endSetup();
?>