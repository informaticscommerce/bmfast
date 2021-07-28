<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


namespace Amasty\QuickOrder\Api;

interface SearchInterface
{
    const CONTAINER_NAME = 'quickorder_search_container';

    /**
     * @param string $searchTerm
     * @return \Amasty\QuickOrder\Api\Search\ProductInterface[]
     */
    public function search(string $searchTerm);
}
