<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Block\Account\Quote\Item\Renderer;

use Amasty\RequestQuote\Traits\Account\Quote\PriceRenderer;

class Grouped extends \Magento\GroupedProduct\Block\Cart\Item\Renderer\Grouped
{
    use PriceRenderer;
}
