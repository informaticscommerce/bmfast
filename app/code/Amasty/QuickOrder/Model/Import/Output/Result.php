<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Import\Output;

class Result
{
    /**
     * @var array
     */
    private $skuArray;

    /**
     * @var array
     */
    private $permanentData;

    /**
     * @var array
     */
    private $productOptions;

    /**
     * @return array
     */
    public function getProductOptions(): array
    {
        return $this->productOptions;
    }

    /**
     * @param array $productOptions
     */
    public function setProductOptions(array $productOptions)
    {
        $this->productOptions = $productOptions;
    }

    /**
     * @return array
     */
    public function getSkuArray(): array
    {
        return $this->skuArray;
    }

    /**
     * @param array $skuArray
     */
    public function setSkuArray(array $skuArray)
    {
        $this->skuArray = $skuArray;
    }

    /**
     * @return array
     */
    public function getPermanentData(): array
    {
        return $this->permanentData;
    }

    /**
     * @param array $permanentData
     */
    public function setPermanentData(array $permanentData)
    {
        $this->permanentData = $permanentData;
    }
}
