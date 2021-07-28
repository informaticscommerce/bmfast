<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Setup\Operation;

use Amasty\RequestQuote\Api\Data\QuoteInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;

class AddShippingField
{
    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable(QuoteInterface::MAIN_TABLE);
        $setup->getConnection('checkout')->addColumn(
            $tableName,
            QuoteInterface::SHIPPING_CAN_BE_MODIFIED,
            [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => false,
                'default' => 1,
                'comment' => 'Determine can be shipping modified on checkout or no'
            ]
        );
        $setup->getConnection('checkout')->addColumn(
            $tableName,
            QuoteInterface::SHIPPING_CONFIGURE,
            [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => false,
                'default' => 0,
                'comment' => 'Determine is shipping configured or no'
            ]
        );
        $setup->getConnection('checkout')->addColumn(
            $tableName,
            QuoteInterface::CUSTOM_FEE,
            [
                'type' => Table::TYPE_DECIMAL,
                'nullable' => false,
                'default' => '0.0000',
                'length'   => '12,4',
                'comment' => 'Custom fee configured for quote'
            ]
        );
        $setup->getConnection('checkout')->addColumn(
            $tableName,
            QuoteInterface::CUSTOM_METHOD_ENABLED,
            [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => false,
                'default' => 0,
                'comment' => 'Determine is custom fee method enabled'
            ]
        );
    }
}
