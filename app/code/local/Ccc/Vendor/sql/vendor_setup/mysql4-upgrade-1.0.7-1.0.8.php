<?php
$installer = $this;
$installer->startSetup();

$installer->getConnection()->dropColumn($this->getTable('vendor'), 'email');

$installer->endSetup();
?>