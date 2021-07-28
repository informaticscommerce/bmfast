<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\ImportExport\Resources;

use Magento\Store\Model\Store;

class GetCustomOptionsValues extends AbstractResource
{
    public function execute(array $skuArray = [], array $columnsToSelect = []): array
    {
        $select = $this->getConnection()->select()->from(
            ['cpo' => $this->getTable('catalog_product_option')],
            $this->getColumnsToSelect($columnsToSelect)
        )->join(
            ['cpe' => $this->getTable('catalog_product_entity')],
            sprintf('cpo.product_id = cpe.%s', $this->getLinkField()),
            []
        )->join(
            ['cpotv' => $this->getTable('catalog_product_option_type_value')],
            'cpo.option_id = cpotv.option_id',
            []
        )->joinLeft(
            ['cpott_default' => $this->getTable('catalog_product_option_type_title')],
            sprintf(
                'cpotv.option_type_id = cpott_default.option_type_id and cpott_default.store_id = %d',
                Store::DEFAULT_STORE_ID
            ),
            []
        )->joinLeft(
            ['cpott_current' => $this->getTable('catalog_product_option_type_title')],
            sprintf(
                'cpotv.option_type_id = cpott_current.option_type_id and cpott_current.store_id = %d',
                $this->getCurrentStoreId()
            ),
            []
        )->where('cpe.sku in (?)', $skuArray);

        return $this->getConnection()->fetchAll($select);
    }
}
