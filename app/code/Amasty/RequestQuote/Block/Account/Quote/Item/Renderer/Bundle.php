<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Block\Account\Quote\Item\Renderer;

use Amasty\RequestQuote\Traits\Account\Quote\PriceRenderer;

class Bundle extends \Magento\Bundle\Block\Checkout\Cart\Item\Renderer
{
    use PriceRenderer;
}
