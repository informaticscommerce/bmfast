<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Plugin\GiftRegistry\Controller\Index;

use Magento\Checkout\Model\Cart as CheckoutCart;
use Magento\GiftRegistry\Controller\Index\Cart;

class CartPlugin
{
    /**
     * @var CheckoutCart
     */
    private $checkoutCart;

    public function __construct(CheckoutCart $checkoutCart)
    {
        $this->checkoutCart = $checkoutCart;
    }

    /**
     * @param Cart $subject
     */
    public function afterExecute(Cart $subject)
    {
        $this->checkoutCart->truncate()->save();
    }
}
