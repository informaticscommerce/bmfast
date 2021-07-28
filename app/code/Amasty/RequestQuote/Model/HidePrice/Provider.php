<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Model\HidePrice;

use Magento\Catalog\Api\Data\ProductInterface;

class Provider
{
    /**
     * @param ProductInterface $product
     * @return bool
     */
    public function isHidePrice(ProductInterface $product)
    {
        return false;
    }
}
