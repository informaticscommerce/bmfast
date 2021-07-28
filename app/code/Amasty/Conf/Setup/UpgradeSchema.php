<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Conf
 */


namespace Amasty\Conf\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var Operation\MoveToPreselect
     */
    private $moveToPreselect;

    /**
     * @var Operation\MoveToOutOfOtock
     */
    private $moveToOutOfOtock;

    public function __construct(
        \Amasty\Conf\Setup\Operation\MoveToPreselect $moveToPreselect,
        \Amasty\Conf\Setup\Operation\MoveToOutOfOtock $moveToOutOfOtock
    ) {
        $this->moveToPreselect = $moveToPreselect;
        $this->moveToOutOfOtock = $moveToOutOfOtock;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '2.5.2', '<')) {
            $this->moveToPreselect->execute($setup);
            $this->moveToOutOfOtock->execute($setup);
        }

        $setup->endSetup();
    }
}
