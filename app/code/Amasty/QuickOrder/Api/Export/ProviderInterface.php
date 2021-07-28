<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Api\Export;

interface ProviderInterface
{
    /**
     * @param array $skuArray
     */
    public function initData(array $skuArray): void;

    /**
     * @param int $optionId
     * @return string|null
     */
    public function getOption(int $optionId): ?string;

    /**
     * @param string $optionId
     * @return string|null
     */
    public function getValue(string $optionId): ?string;
}
