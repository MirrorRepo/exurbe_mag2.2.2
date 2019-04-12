<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Setup\Operation;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;
use Amasty\Gdpr\Api\Data\CookiePolicyConsentInterface;

class CreateCookiePolicyConsentTable
{
    const TABLE_NAME = 'amasty_gdpr_cookie_policy_consent';

    /**
     * @param SchemaSetupInterface $setup
     *
     * @throws \Zend_Db_Exception
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->createTable(
            $this->createTable($setup)
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     *
     * @return Table
     * @throws \Zend_Db_Exception
     */
    private function createTable(SchemaSetupInterface $setup)
    {
        $table = $setup->getTable(self::TABLE_NAME);
        $customerTable = $setup->getTable('customer_entity');
        $websiteTable = $setup->getTable('store_website');

        return $setup->getConnection()
            ->newTable(
                $table
            )->setComment(
                'Amasty GDPR Table with cookie consent customers'
            )->addColumn(
                CookiePolicyConsentInterface::ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary'  => true
                ],
                'Id'
            )->addColumn(
                CookiePolicyConsentInterface::CUSTOMER_ID,
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Customer Id'
            )->addColumn(
                CookiePolicyConsentInterface::CONSENT_TYPE,
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => false
                ],
                'Consent Type'
            )->addColumn(
                CookiePolicyConsentInterface::DATE_RECIEVED,
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false,
                    'default'  => Table::TIMESTAMP_INIT
                ],
                'Date Recieved'
            )->addColumn(
                CookiePolicyConsentInterface::CONSENT_STATUS,
                Table::TYPE_SMALLINT,
                null,
                [
                    'nullable' => false
                ],
                'Consent Status'
            )->addColumn(
                CookiePolicyConsentInterface::WEBSITE,
                Table::TYPE_SMALLINT,
                null,
                [
                    'nullable' => true,
                    'unsigned' => true
                ],
                'Website'
            )->addColumn(
                CookiePolicyConsentInterface::CUSTOMER_IP,
                Table::TYPE_TEXT,
                15,
                [
                    'nullable' => false
                ],
                'Customer Ip Address'
            )->addForeignKey(
                $setup->getFkName(
                    $table,
                    CookiePolicyConsentInterface::CUSTOMER_ID,
                    $customerTable,
                    'entity_id'
                ),
                CookiePolicyConsentInterface::CUSTOMER_ID,
                $customerTable,
                'entity_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $setup->getFkName(
                    $table,
                    CookiePolicyConsentInterface::WEBSITE,
                    $websiteTable,
                    'website_id'
                ),
                CookiePolicyConsentInterface::WEBSITE,
                $websiteTable,
                'website_id',
                Table::ACTION_CASCADE
            );
    }
}
