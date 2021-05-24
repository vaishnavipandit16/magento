<?php
$installer = $this;

$installer->startSetup();

$installer->addAttribute(Ccc_Vendor_Model_Vendor::ENTITY, 'password_hash', [
	'group' => 'General',
	'input' => 'text',
	'type' => 'varchar',
	'label' => 'password',
	'backend' => '',
	'visible' => 1,
	'required' => 0,
	'user_defined' => 1,
	'searchable' => 1,
	'filterable' => 0,
	'comparable' => 1,
	'visible_on_front' => 1,
	'visible_in_advanced_search' => 0,
	'is_html_allowed_on_front' => 1,
	'global' => 1,
]);

$installer->endSetup();
?>