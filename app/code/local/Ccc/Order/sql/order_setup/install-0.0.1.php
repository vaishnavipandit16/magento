<?php
$installer = $this;
$installer->startSetup();

$installer->run("DROP TABLE IF EXISTS `{$installer->getTable('order/cart')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('order/cart_item')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('order/cart_address')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('order/order')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('order/order_address')}`;
    DROP TABLE IF EXISTS `{$installer->getTable('order/order_item')}`;"
);

//Cart Table
$cart = $installer->getConnection()
            ->newTable($installer->getTable('check/cart'))
            ->addColumn('cart_id',
                Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                array(
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ), 'Id'
            )
            ->addColumn('customer_id',
                Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                array(
                    'nullable' => false
                ), 'Customer Id'
            )
            ->addColumn('session_id',
                Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                array(
                    'nullable' => true
                ), 'Session Id'
            )
            ->addColumn('total',
                Varien_Db_Ddl_Table::TYPE_DECIMAL,null,
                array(
                    'nullable' => false
                ), 'Total'
            )
            ->addColumn('payment_method_code',
                Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                array(
                    'nullable' => false
                ), 'Payment Method Code'
            )
            ->addColumn('shipping_method_code',
                Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                array(
                    'nullable' => false
                ), 'Shipping Method Code'
            )
            ->addColumn('shipping_amount',
                Varien_Db_Ddl_Table::TYPE_DECIMAL,null,
                array(
                    'nullable' => false
                ), 'Shipping Amount'
            )
            ->addColumn('created_at',
                Varien_Db_Ddl_Table::TYPE_DATETIME,null,
                array(
                    'nullable' => false
                ), 'Created At'
            );
            // ->addForeignKey(
            //     $installer->getFkName(
            //         'check/cart',
            //         'customer_id',
            //         'customer/entity',
            //         'entity_id'
            //     ),
            //     'customer_id',
            //     $installer->getTable('customer/entity'),
            //     'entity_id',
            //     Varien_Db_Ddl_Table::ACTION_CASCADE,
            //     Varien_Db_Ddl_Table::ACTION_CASCADE
            // );

$installer->getConnection()->createTable($cart);

//Cart Address
$cart_address = $installer->getConnection()
                    ->newTable($installer->getTable('check/cart_address'))
                    ->addColumn('cart_address_id',
                        Varien_DB_Ddl_Table::TYPE_INTEGER,null,
                        array(
                            'identity' => true,
                            'unsigned' => true,
                            'nullable' => false,
                            'primary' => true
                        ), 'Cart Address Id'
                    )
                    ->addColumn('cart_id',
                        Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                        array(
                            'nullable' => false
                        ), 'Cart Id'
                    )
                    ->addColumn('address_id',
                        Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                        array(
                            'nullable' => false
                        ), 'Address Id'
                    )
                    ->addColumn('address_type',
                        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                        array(
                            'nullable' => false
                        ), 'Address Type'
                    )
                    ->addColumn('address',
                        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                        array(
                            'nullable' => false
                        ), 'Address Type'
                    )
                    ->addColumn('city',
                        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                        array(
                            'nullable' => false
                        ), 'City'
                    )
                    ->addColumn('state',
                        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                        array(
                            'nullable' => false
                        ), 'State'
                    )
                    ->addColumn('country',
                        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                        array(
                            'nullable' => false
                        ), 'Country'
                    )
                    ->addColumn('zipcode',
                        Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                        array(
                            'nullable' => false
                        ), 'Zipcode'
                    )
                    ->addColumn('same_as_billing',
                        Varien_Db_Ddl_Table::TYPE_TINYINT,null,
                        array(
                            'nullable' => false
                        ), 'Same As Billing'
                    );
                    // ->addForeignKey(
                    //     $installer->getFkName(
                    //         'check/cart_item',
                    //         'cart_id',
                    //         'check/cart',
                    //         'cart_id'
                    //     ),
                    //     'cart_id',
                    //     $installer->getTable('check/cart'),
                    //     'cart_id',
                    //     Varien_Db_Ddl_Table::ACTION_CASCADE,
                    //     Varien_Db_Ddl_Table::ACTION_CASCADE
                    // );
            
$installer->getConnection()->createTable($cart_address);

//Cart_Item
$cart_item = $installer->getConnection()
            ->newTable($installer->getTable('check/cart_item'))
            ->addColumn('cart_item_id',
                Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                array(
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ), 'Cart Item Id'
            )
            ->addColumn('cart_id',
                Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                array(
                    'nullable' => false,
                ), 'Cart Id'
            )
            ->addColumn('product_id',
                Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                array(
                    'nullable' => false,
                ), 'Product Id'
            )
            ->addColumn('quantity',
                Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                array(
                    'nullable' => false,
                ), 'Quantity'
            )
            ->addColumn('base_price',
                Varien_Db_Ddl_Table::TYPE_DECIMAL,null,
                array(
                    'nullable' => false,
                ), 'Base Price'
            )
            ->addColumn('price',
                Varien_Db_Ddl_Table::TYPE_DECIMAL,null,
                array(
                    'nullable' => false,
                ), 'Price'
            )
            ->addColumn('discount',
                Varien_Db_Ddl_Table::TYPE_DECIMAL,null,
                array(
                    'nullable' => false,
                ), 'Discount'
            )
            ->addColumn('created_at',
                Varien_Db_Ddl_Table::TYPE_DATETIME,null,
                array(
                    'nullable' => false,
                ), 'Created At'
            );

$installer->getConnection()->createTable($cart_item);


//Order Table
$order = $installer->getConnection()
            ->newTable($installer->getTable('check/order'))
            ->addColumn('order_id',
                Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                array(
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ), 'Id'
            )
            ->addColumn('customer_id',
                Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                array(
                    'nullable' => false
                ), 'Customer Id'
            )
            ->addColumn('discount',
                Varien_Db_Ddl_Table::TYPE_DECIMAL,null,
                array(
                    'nullable' => false
                ), 'Customer Id'
            )
            ->addColumn('total',
                Varien_Db_Ddl_Table::TYPE_DECIMAL,null,
                array(
                    'nullable' => false
                ), 'Total'
            )
            ->addColumn('payment_method_code',
                Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                array(
                    'nullable' => false
                ), 'Payment Method Code'
            )
            ->addColumn('shipping_method_code',
                Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                array(
                    'nullable' => false
                ), 'Shipping Method Code'
            )
            ->addColumn('shipping_amount',
                Varien_Db_Ddl_Table::TYPE_DECIMAL,null,
                array(
                    'nullable' => false
                ), 'Shipping Amount'
            )
            ->addColumn('created_at',
                Varien_Db_Ddl_Table::TYPE_DATETIME,null,
                array(
                    'nullable' => false
                ), 'Created At'
            );

$installer->getConnection()->createTable($order);

//Order_Item
$order_item = $this->getConnection()
                ->newTable($installer->getTable('check/order_item'))
                ->addColumn('order_item_id',
                    Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                    array(
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ), 'Order Item Id'
                )
                ->addColumn('order_id',
                    Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                    array(
                        'nullable' => false,
                    ), 'Order Id'
                )
                ->addColumn('product_id',
                    Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                    array(
                        'nullable' => false,
                    ), 'Product Id'
                )
                ->addColumn('quantity',
                    Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                    array(
                        'nullable' => false,
                    ), 'Qunatity'
                )
                ->addColumn('price',
                    Varien_Db_Ddl_Table::TYPE_DECIMAL,null,
                    array(
                        'nullable' => false,
                    ), 'Price'
                )
                ->addColumn('discount',
                    Varien_Db_Ddl_Table::TYPE_DECIMAL,null,
                    array(
                        'nullable' => false,
                    ), 'Discount'
                )
                ->addColumn('created_at',
                    Varien_Db_Ddl_Table::TYPE_DATETIME,null,
                    array(
                        'nullable' => false,
                    ), 'Created At'
                );
$installer->getConnection()->createTable($order_item);

//Order_Address
$order_address = $installer->getConnection()
                    ->newTable($installer->getTable('check/order_address'))
                    ->addColumn('order_address_id',
                        Varien_DB_Ddl_Table::TYPE_INTEGER,null,
                        array(
                            'identity' => true,
                            'unsigned' => true,
                            'nullable' => false,
                            'primary' => true
                        ), 'Order Address Id'
                    )
                    ->addColumn('order_id',
                        Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                        array(
                            'nullable' => false
                        ), 'Order Id'
                    )
                    ->addColumn('address_id',
                        Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                        array(
                            'nullable' => false
                        ), 'Address Id'
                    )
                    ->addColumn('address_type',
                        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                        array(
                            'nullable' => false
                        ), 'Address Type'
                    )
                    ->addColumn('address',
                        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                        array(
                            'nullable' => false
                        ), 'Address Type'
                    )
                    ->addColumn('city',
                        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                        array(
                            'nullable' => false
                        ), 'City'
                    )
                    ->addColumn('state',
                        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                        array(
                            'nullable' => false
                        ), 'State'
                    )
                    ->addColumn('country',
                        Varien_Db_Ddl_Table::TYPE_VARCHAR,null,
                        array(
                            'nullable' => false
                        ), 'Country'
                    )
                    ->addColumn('zipcode',
                        Varien_Db_Ddl_Table::TYPE_INTEGER,null,
                        array(
                            'nullable' => false
                        ), 'Zipcode'
                    )
                    ->addColumn('same_as_billing',
                        Varien_Db_Ddl_Table::TYPE_TINYINT,null,
                        array(
                            'nullable' => false
                        ), 'Same As Billing'
                    );
            
$installer->getConnection()->createTable($order_address);

$installer->endSetup();
?>