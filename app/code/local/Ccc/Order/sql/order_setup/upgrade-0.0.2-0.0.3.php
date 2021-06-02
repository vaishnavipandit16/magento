<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('cart_address')}
    CHANGE `state` `region` text;
");

$installer->endSetup();
?>