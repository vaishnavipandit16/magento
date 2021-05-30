<?php

$installer = $this;
$installer->startSetup();

$installer->run("ALTER TABLE `order` ADD COLUMN `customer_name` text");

$installer->endSetup();
?>