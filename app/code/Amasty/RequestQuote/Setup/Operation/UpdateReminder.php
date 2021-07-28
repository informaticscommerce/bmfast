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

class UpdateReminder
{
    public function execute(SchemaSetupInterface $setup)
    {
        $setup->startSetup();

        $setup->getConnection('checkout')->addColumn(
            $setup->getTable('amasty_quote'),
            QuoteInterface::REMINDER_SEND,
            [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => false,
                'default' => 0,
                'comment' => 'Reminder Send'
            ]
        );

        $setup->endSetup();
    }
}
