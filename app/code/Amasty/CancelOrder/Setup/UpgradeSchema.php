<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CancelOrder
 */

declare(strict_types=1);

namespace Amasty\CancelOrder\Setup;

use Amasty\CancelOrder\Setup\UpgradeSchema\ModifyOrderIdType;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var ModifyOrderIdType
     */
    private $modifyOrderIdType;

    public function __construct(
        ModifyOrderIdType $modifyOrderIdType
    ) {
        $this->modifyOrderIdType = $modifyOrderIdType;
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $setup->startSetup();

        if (!$context->getVersion()
            || version_compare($context->getVersion(), '1.1.1', '<')
        ) {
            $this->modifyOrderIdType->execute($setup);
        }

        $setup->endSetup();
    }
}
