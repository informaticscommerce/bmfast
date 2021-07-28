<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Setup\Operation;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @class AddTypeIndex
 *
 * Class used for key installation for catalog_product_entity table.
 * This key helps to speed up select sql queries from catalog_product_entity with type_id filter
 */
class AddTypeIdIndex
{
    const FIELD_NAME = 'type_id';

    /**
     * @param SchemaSetupInterface $setup
     */
    public function execute(SchemaSetupInterface $setup): void
    {
        $table = $setup->getTable('catalog_product_entity');
        $setup->getConnection()->addIndex(
            $table,
            $setup->getIdxName($table, [self::FIELD_NAME]),
            [self::FIELD_NAME],
            AdapterInterface::INDEX_TYPE_INDEX
        );
    }
}
