<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Setup\Operation;

use Amasty\RequestQuote\Api\Data\QuoteInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class MoveTable
{
    /**
     * @param SchemaSetupInterface $setup
     *
     * @throws \Zend_Db_Statement_Exception
     */
    public function execute(SchemaSetupInterface $setup)
    {
        $setup->startSetup();
        $tableName = $setup->getTable(QuoteInterface::MAIN_TABLE);
        $checkoutConnection = $setup->getConnection('checkout');

        if (!$checkoutConnection->isTableExists($tableName)) {
            $defaultConnection = $setup->getConnection();
            $checkoutConnection->query($defaultConnection->getCreateTable($tableName));
            $select = $defaultConnection->select()->from($tableName);
            $data = $defaultConnection->query($select)->fetchAll();

            if (count($data)) {
                $columns = array_keys($data[0]);
                $checkoutConnection->insertArray($tableName, $columns, $data);
            }

            $defaultConnection->dropTable($tableName);
        }

        $setup->endSetup();
    }
}
