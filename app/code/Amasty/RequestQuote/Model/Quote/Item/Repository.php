<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Model\Quote\Item;

/**
 * Class Repository
 *
 * use physical class instead of virtual type because https://github.com/magento/magento2/issues/14950
 */
class Repository extends \Magento\Quote\Model\Quote\Item\Repository
{
    const VERSION234 = '2.3.4';

    public function __construct(
        \Amasty\Base\Model\MagentoVersion $magentoVersion,
        \Amasty\RequestQuote\Api\QuoteRepositoryInterface $quoteRepository,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Quote\Api\Data\CartItemInterfaceFactory $itemDataFactory,
        \Magento\Quote\Model\Quote\Item\CartItemOptionsProcessor $cartItemOptionsProcessor,
        array $cartItemProcessors = []
    ) {
        if (version_compare($magentoVersion->get(), self::VERSION234, '>=')) {
            $params = [
                $quoteRepository,
                $productRepository,
                $itemDataFactory,
                $cartItemOptionsProcessor,
                $cartItemProcessors
            ];
        } else {
            $params = [
                $quoteRepository,
                $productRepository,
                $itemDataFactory,
                $cartItemProcessors
            ];
        }

        // use this construction because in Magento 2.3.4 parent construct need 5 params instead of 4 in early
        // @codingStandardsIgnoreLine
        call_user_func_array('parent::__construct', $params);
    }
}
