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

class AddOriginalPrice
{
    public function execute(SchemaSetupInterface $setup): void
    {
        $tableName = $setup->getTable(QuoteInterface::MAIN_TABLE);
        $setup->getConnection('checkout')->addColumn(
            $tableName,
            QuoteInterface::SUM_ORIGINAL_PRICE,
            [
                'type' => Table::TYPE_INTEGER,
                'nullable' => false,
                'default' => 0,
                'comment' => 'Sum Original Price'
            ]
        );
    }
}
