<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Setup\Operation;

use Amasty\RequestQuote\Api\Data\QuoteInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;

class DiscountColumns
{
    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $setup->startSetup();
        $table = $setup->getTable('amasty_quote');

        if (!$setup->getConnection('checkout')->tableColumnExists($table, QuoteInterface::DISCOUNT)) {
            $setup->getConnection('checkout')->addColumn(
                $table,
                QuoteInterface::DISCOUNT,
                [
                    'type' => Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length'   => '4,2',
                    'comment' => 'Discount applied for all items'
                ]
            );
        }
        if (!$setup->getConnection('checkout')->tableColumnExists($table, QuoteInterface::SURCHARGE)) {
            $setup->getConnection('checkout')->addColumn(
                $table,
                QuoteInterface::SURCHARGE,
                [
                    'type' => Table::TYPE_DECIMAL,
                    'nullable' => true,
                    'length'   => '4,2',
                    'comment' => 'Surcharge applied for all items'
                ]
            );
        }

        $setup->endSetup();
    }
}
