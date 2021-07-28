<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CancelOrder
 */

declare(strict_types=1);

namespace Amasty\CancelOrder\Setup\UpgradeSchema;

use Amasty\CancelOrder\Api\Data\CancelOrderInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\SchemaSetupInterface;

class ModifyOrderIdType
{
    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup): void
    {
        $connection = $setup->getConnection();
        $table = $setup->getTable(CancelOrderInterface::TABLE_NAME);
        $connection->modifyColumn(
            $table,
            CancelOrderInterface::ORDER_ID,
            [
                'type' => Table::TYPE_INTEGER,
                'nullable' => false,
                'comment' => 'Order Id'
            ]
        );
    }
}
