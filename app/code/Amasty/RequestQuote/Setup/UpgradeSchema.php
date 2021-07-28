<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var Operation\Reminder
     */
    private $reminder;

    /**
     * @var Operation\SubmitedDate
     */
    private $submitedDate;

    /**
     * @var Operation\ColumnUpdate
     */
    private $columnUpdate;

    /**
     * @var Operation\DiscountColumns
     */
    private $discountColumns;

    /**
     * @var Operation\UpdateReminder
     */
    private $updateReminder;

    /**
     * @var Operation\AddShippingField
     */
    private $addShippingField;

    /**
     * @var Operation\MoveTable
     */
    private $moveTable;

    /**
     * @var Operation\AddTypeIdIndex
     */
    private $addTypeIdIndex;

    /**
     * @var Operation\AddOriginalPrice
     */
    private $addOriginalPrice;

    public function __construct(
        Operation\Reminder $reminder,
        Operation\SubmitedDate $submitedDate,
        Operation\ColumnUpdate $columnUpdate,
        Operation\DiscountColumns $discountColumns,
        Operation\UpdateReminder $updateReminder,
        Operation\AddShippingField $addShippingField,
        Operation\MoveTable $moveTable,
        Operation\AddTypeIdIndex $addTypeIdIndex,
        Operation\AddOriginalPrice $addOriginalPrice
    ) {
        $this->reminder = $reminder;
        $this->submitedDate = $submitedDate;
        $this->columnUpdate = $columnUpdate;
        $this->discountColumns = $discountColumns;
        $this->updateReminder = $updateReminder;
        $this->addShippingField = $addShippingField;
        $this->moveTable = $moveTable;
        $this->addTypeIdIndex = $addTypeIdIndex;
        $this->addOriginalPrice = $addOriginalPrice;
    }

    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            $this->reminder->execute($setup);
        }

        if (version_compare($context->getVersion(), '1.4.4', '<')) {
            $this->submitedDate->execute($setup);
        }

        if (version_compare($context->getVersion(), '1.5.0', '<')) {
            $this->columnUpdate->execute($setup);
        }

        if (version_compare($context->getVersion(), '1.6.0', '<')) {
            $this->discountColumns->execute($setup);
        }

        if (version_compare($context->getVersion(), '2.0.3', '<')) {
            $this->updateReminder->execute($setup);
        }

        if (version_compare($context->getVersion(), '2.2.0', '<')) {
            $this->addShippingField->execute($setup);
        }

        if (version_compare($context->getVersion(), '2.2.1', '<')) {
            $this->moveTable->execute($setup);
        }

        if (version_compare($context->getVersion(), '2.2.5', '<')) {
            $this->addTypeIdIndex->execute($setup);
        }

        if (version_compare($context->getVersion(), '2.3.1', '<')) {
            $this->addOriginalPrice->execute($setup);
        }
    }
}
