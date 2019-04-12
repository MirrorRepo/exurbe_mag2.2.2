<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Gdpr
 */


namespace Amasty\Gdpr\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var Operation\UpgradeTo130
     */
    private $upgradeTo130;

    /**
     * @var Operation\CreateCookiePolicyConsentTable
     */
    private $consentTable;

    /**
     * UpgradeSchema constructor.
     *
     * @param Operation\UpgradeTo130 $upgradeTo130
     */
    public function __construct(
        Operation\UpgradeTo130 $upgradeTo130,
        Operation\CreateCookiePolicyConsentTable $consentTable
    ) {
        $this->upgradeTo130 = $upgradeTo130;
        $this->consentTable = $consentTable;
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (!$context->getVersion() || version_compare($context->getVersion(), '1.3.0', '<')) {
            $this->upgradeTo130->execute($setup);
        }

        if ($context->getVersion() && version_compare($context->getVersion(), '1.4.1', '<')) {
            $this->consentTable->execute($setup);
        }

        $setup->endSetup();
    }
}
