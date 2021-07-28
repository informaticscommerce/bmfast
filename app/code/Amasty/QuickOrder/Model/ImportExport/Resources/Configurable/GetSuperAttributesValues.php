<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\ImportExport\Resources\Configurable;

use Amasty\QuickOrder\Model\ImportExport\Resources\AbstractResource;

use Magento\Store\Model\Store;

class GetSuperAttributesValues extends AbstractResource
{
    public function execute(array $skuArray = [], array $columnsToSelect = []): array
    {
        $select = $this->getConnection()->select()->from(
            ['cpsa' => $this->getTable('catalog_product_super_attribute')],
            $this->getColumnsToSelect($columnsToSelect)
        )->join(
            ['eao' => $this->getTable('eav_attribute_option')],
            'cpsa.attribute_id = eao.attribute_id',
            []
        )->joinLeft(
            ['eaol_default' => $this->getTable('eav_attribute_option_value')],
            sprintf(
                'eao.option_id = eaol_default.option_id and eaol_default.store_id = %d',
                Store::DEFAULT_STORE_ID
            ),
            []
        )->joinLeft(
            ['eaol_current' => $this->getTable('eav_attribute_option_value')],
            sprintf(
                'eao.option_id = eaol_current.option_id and eaol_current.store_id = %d',
                $this->getCurrentStoreId()
            ),
            []
        )->group('cpsa.attribute_id')->group('eao.option_id');

        return $this->getConnection()->fetchPairs($select);
    }
}
