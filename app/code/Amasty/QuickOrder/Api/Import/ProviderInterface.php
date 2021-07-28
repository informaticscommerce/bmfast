<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


namespace Amasty\QuickOrder\Api\Import;

interface ProviderInterface
{
    /**
     * @param array $skuArray
     */
    public function initData(array $skuArray);

    /**
     * @param string $title
     * @param string $sku
     * @return string|null
     */
    public function getOption(string $title, string $sku);

    /**
     * @param string $title
     * @param string $sku
     * @return string|null
     */
    public function getValue(string $title, string $sku);

    /**
     * @return string
     */
    public function getCode();
}
