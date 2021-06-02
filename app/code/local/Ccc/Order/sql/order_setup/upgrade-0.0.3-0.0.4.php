<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('cart_item')}
    ADD  COLUMN `name` varchar(50);
");

$installer->endSetup();
?>