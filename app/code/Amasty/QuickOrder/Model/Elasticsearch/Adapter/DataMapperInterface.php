<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


namespace Amasty\QuickOrder\Model\Elasticsearch\Adapter;

interface DataMapperInterface
{
    /**
     * Prepare index data for using in search engine metadata
     *
     * @param $entityId
     * @return mixed
     */
    public function getValue($entityId);

    /**
     * @return bool
     */
    public function isAllowed();

    /**
     * Initialize data for products
     *
     * @param array $productIds
     * @param int $storeId
     */
    public function load(array $productIds, int $storeId);
}
