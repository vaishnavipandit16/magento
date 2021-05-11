<?php

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
        ->newTable($installer->getTable('vendor/product_attribute_group'))
        ->addColumn('group_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,null,
        array(
            'nullable'=>false,
            'identity'=>true,
            'unsigned'=>true,
            'primary'=>true,

        ),'Id'
        )
        ->addColumn('entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,null,
        array(
            'nullable'=>false,
        ),'Vendor Id'
        )
        ->addColumn('attribute_group_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,null,
        array(
            'nullable'=>false,
        ),'Attribute Group Id'
        )
        ->addColumn('attribute_group_name',
        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
        array(
            'nullable'=>false,
        ),'Attribute Group Name')

        ->addForeignKey(
            $installer->getFkName('vendor/product_attribute_group', 'entity_id', 'vendor/vendor', 'entity_id'),
            'entity_id',
            $installer->getTable('vendor/vendor'),
            'entity_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->setComment('Vendor Id');


$installer->getConnection()->createTable($table);
$installer->endSetup();

?>