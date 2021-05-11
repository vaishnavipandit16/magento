<?php
$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
            ->newTable($installer->getTable('ccc_salesman/data'))
            ->addColumn('salesman_id',
                Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                array(
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ), 'Id'
            )
            ->addColumn('code',
                Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                array(
                    'nullable' => false
                ), 'code'
            )
            ->addColumn('name',
                Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                array(
                    'nullable' => false
                ), 'name'
            )
            ->addColumn('email',
                Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                array(
                    'nullable' => false
                ), 'email'
            )
            ->addColumn('mobile',
                Varien_Db_Ddl_Table::TYPE_NUMERIC,null,
                array(
                    'nullable' => false
                ), 'mobile'
            )
            ->addColumn('created_date',
                Varien_DB_Ddl_Table::TYPE_DATETIME,null,
                array(
                    'nullable' => false
                ), 'created_date'
            );
$installer->getConnection()->createTable($table);
$installer->endSetup();
?>