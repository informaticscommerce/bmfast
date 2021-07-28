<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\ImportExport\Resources;

class GetProductTypes extends AbstractResource
{
    /**
     * @var string[]
     */
    protected $columnsToSelect = [
        'sku',
        'type_id'
    ];

    public function execute(array $skuArray = [], array $columnsToSelect = []): array
    {
        $select = $this->getConnection()->select()->from(
            $this->getTable('catalog_product_entity'),
            $this->columnsToSelect
        )->where('sku IN (?)', $skuArray);

        return $this->getConnection()->fetchPairs($select);
    }
}
