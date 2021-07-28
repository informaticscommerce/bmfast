<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Block\Account\Quote\Item\Renderer;

use Amasty\RequestQuote\Traits\Account\Quote\PriceRenderer;

class Simple extends \Magento\Checkout\Block\Cart\Item\Renderer
{
    use PriceRenderer;
}
