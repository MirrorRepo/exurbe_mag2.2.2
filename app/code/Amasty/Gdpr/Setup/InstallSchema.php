<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Setup;

use Amasty\Gdpr\Setup\Operation\CreateActionLogTable;
use Amasty\Gdpr\Setup\Operation\CreateConsentLogTable;
use Amasty\Gdpr\Setup\Operation\CreateConsentQueueTable;
use Amasty\Gdpr\Setup\Operation\CreateDeleteRequestTable;
use Amasty\Gdpr\Setup\Operation\CreatePolicyContentTable;
use Amasty\Gdpr\Setup\Operation\CreatePolicyTable;
use Amasty\Gdpr\Setup\Operation\CreateCookiePolicyConsentTable;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @var CreateConsentLogTable
     */
    private $consentLogTable;

    /**
     * @var CreateDeleteRequestTable
     */
    private $deleteRequestTable;

    /**
     * @var CreatePolicyTable
     */
    private $policyTable;

    /**
     * @var CreatePolicyContentTable
     */
    private $policyContentTable;

    /**
     * @var CreateConsentQueueTable
     */
    private $consentQueueTable;

    /**
     * @var CreateActionLogTable
     */
    private $actionLogTable;

    /**
     * @var CreateCookiePolicyConsentTable
     */
    private $cookiePolicyConsentTable;

    public function __construct(
        CreateConsentLogTable $consentLogTable,
        CreateDeleteRequestTable $deleteRequestTable,
        CreatePolicyTable $policyTable,
        CreatePolicyContentTable $policyContentTable,
        CreateConsentQueueTable $consentQueueTable,
        CreateActionLogTable $actionLogTable,
        CreateCookiePolicyConsentTable $cookiePolicyConsentTable
    ) {
        $this->consentLogTable = $consentLogTable;
        $this->deleteRequestTable = $deleteRequestTable;
        $this->policyTable = $policyTable;
        $this->policyContentTable = $policyContentTable;
        $this->consentQueueTable = $consentQueueTable;
        $this->actionLogTable = $actionLogTable;
        $this->cookiePolicyConsentTable = $cookiePolicyConsentTable;
    }

    /**
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     *
     * @throws \Zend_Db_Exception
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    //@codingStandardsIgnoreStart
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        //@codingStandardsIgnoreStop
        $setup->startSetup();

        $this->consentLogTable->execute($setup);
        $this->deleteRequestTable->execute($setup);
        $this->policyTable->execute($setup);
        $this->policyContentTable->execute($setup);
        $this->consentQueueTable->execute($setup);
        $this->actionLogTable->execute($setup);
        $this->cookiePolicyConsentTable->execute($setup);

        $setup->endSetup();
    }
}
