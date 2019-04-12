<?php
/**
 * A Magento 2 module named Magetwo/Giftcard
 * Copyright (C) 2017  2017
 * 
 * This file included in Magetwo/Giftcard is licensed under OSL 3.0
 * 
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Magetwo\Giftcard\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        $table_magetwo_giftcard_giftcode = $setup->getConnection()->newTable($setup->getTable('magetwo_giftcard_giftcode'));

        
        $table_magetwo_giftcard_giftcode->addColumn(
            'giftcode_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array('identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,),
            'Entity ID'
        );
        

        
        $table_magetwo_giftcard_giftcode->addColumn(
            'code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'code'
        );
        

        $setup->getConnection()->createTable($table_magetwo_giftcard_giftcode);

        $setup->endSetup();
    }
}
