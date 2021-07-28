<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Api\Export;

interface ResourceInterface
{
    /**
     * @param array $skuArray
     * @return array
     */
    public function execute(array $skuArray = []): array;
}
