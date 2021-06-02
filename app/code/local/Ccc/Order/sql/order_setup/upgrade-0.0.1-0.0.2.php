<?php
$installer = $this;
$installer->startSetup();

$installer->run("
ALTER TABLE {$this->getTable('cart_address')}
    CHANGE `address` `street` text;
");

$installer->run("
ALTER TABLE {$this->getTable('cart_address')}
    CHANGE `country` `country_id` text;
");

$installer->run("
ALTER TABLE {$this->getTable('cart_address')}
    CHANGE `zipcode` `postcode` int(11);
");
$installer->endSetup();
?>