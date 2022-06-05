<?php
/**
 * BSeller Platform | B2W - Companhia Digital
 *
 * Do not edit this file if you want to update this module for future new versions.
 *
 * @category  BSeller
 * @package   BSeller_SkyHub
 *
 * @copyright Copyright (c) 2018 B2W Digital - BSeller Platform. (http://www.bseller.com.br)
 *
 * Access https://ajuda.skyhub.com.br/hc/pt-br/requests/new for questions and other requests.
 */

/**
 * @var BSeller_SkyHub_Model_Resource_Setup $this
 * @var Magento_Db_Adapter_Pdo_Mysql        $conn
 */

$this->startSetup();
//**********************************************************************************************************************
// Install bseller_skyhub/customer_attributes_mapping
//**********************************************************************************************************************
$tableName = (string) $this->getTable('bseller_skyhub/customer_attributes_mapping');

/** @var Varien_Db_Ddl_Table $table */
$table = $this->newTable($tableName)
    ->addColumn(
        'skyhub_code',
        $this::TYPE_TEXT,
        255,
        array(
            'nullable' => false,
        )
    )
    ->addColumn(
        'skyhub_label',
        $this::TYPE_TEXT,
        255,
        array(
            'nullable' => true,
        )
    )
    ->addColumn(
        'skyhub_description',
        $this::TYPE_TEXT,
        null,
        array(
            'nullable' => true,
        )
    )
    ->addColumn(
        'enabled',
        $this::TYPE_BOOLEAN,
        1,
        array(
            'nullable' => false,
            'default' => true
        )
    )
    ->addColumn(
        'cast_type',
        $this::TYPE_TEXT,
        255,
        array(
            'nullable' => false
        )
    )
    ->addColumn(
        'validation',
        $this::TYPE_TEXT,
        null,
        array(
            'nullable' => true
        )
    )
    ->addColumn(
        'attribute_id',
        $this::TYPE_INTEGER,
        255,
        array(
            'nullable' => true,
            'default' => null
        )
    )
    ->addColumn(
        'required',
        $this::TYPE_BOOLEAN,
        1,
        array(
            'nullable' => false,
            'default' => true
        )
    )
    ->addColumn(
        'editable',
        $this::TYPE_BOOLEAN,
        1,
        array(
            'nullable' => false,
            'default' => true
        )
    )
    ->addColumn(
        'has_options',
        $this::TYPE_BOOLEAN,
        1,
        array(
            'nullable' => false,
            'default' => false
        )
    );

$this->addTimestamps($table);
$conn->createTable($table);

$this->addIndex(array('skyhub_code', 'attribute_id'), $tableName);
$this->addForeignKey(
    $tableName,
    'attribute_id',
    'eav/attribute',
    'attribute_id',
    $this::FK_ACTION_SET_NULL, $this::FK_ACTION_SET_NULL
);

//**********************************************************************************************************************
// Install bseller_skyhub/customer_attributes_mapping_options
//**********************************************************************************************************************
$tableName = (string) $this->getTable('bseller_skyhub/customer_attributes_mapping_options');

/** @var Varien_Db_Ddl_Table $table */
$table = $this->newTable($tableName)
    ->addColumn(
        'customer_attributes_mapping_id',
        $this::TYPE_INTEGER,
        255,
        array(
            'nullable' => false
        )
    )
    ->addColumn(
        'skyhub_code',
        $this::TYPE_VARCHAR,
        255,
        array(
            'nullable' => false
        )
    )
    ->addColumn(
        'skyhub_label',
        $this::TYPE_VARCHAR,
        255,
        array(
            'nullable' => false
        )
    )
    ->addColumn(
        'magento_value',
        $this::TYPE_VARCHAR,
        255,
        array(
            'nullable' => true,
            'default' => null
        )
    );

$conn->createTable($table);

$this->addIndex(array('customer_attributes_mapping_id', 'skyhub_code'), $tableName);
$this->addForeignKey(
    $tableName,
    'customer_attributes_mapping_id',
    'bseller_skyhub/customer_attributes_mapping',
    'id'
);

//**********************************************************************************************************************
// Update sales/order
//**********************************************************************************************************************
$columnName = 'bseller_skyhub_json';
$tableName = $this->getTable('sales/order');
$columnConfig = array(
    'type' => $this::TYPE_TEXT,
    'nullable' => true,
    'default' => null,
    'after' => 'bseller_skyhub_interest',
    'comment' => 'SkyHub Order Json',
);
$conn->addColumn($tableName, $columnName, $columnConfig);

$this->endSetup();
