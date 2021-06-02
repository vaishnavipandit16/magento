<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('cart_address')}
    CHANGE `country_id` `country` text;
");

$installer->run("
ALTER TABLE {$this->getTable('order_address')}
    CHANGE `country_id` `country` text;
");

$installer->endSetup();

?>