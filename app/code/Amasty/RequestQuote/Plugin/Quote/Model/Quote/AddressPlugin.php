<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Plugin\Quote\Model\Quote;

use Amasty\RequestQuote\Api\Data\QuoteInterface;
use Amasty\RequestQuote\Api\QuoteRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Address;

class AddressPlugin
{
    /**
     * @var QuoteRepositoryInterface
     */
    private $quoteRepository;

    public function __construct(QuoteRepositoryInterface $quoteRepository)
    {
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param Address $shippingAddress
     * @return null
     */
    public function beforeRequestShippingRates(Address $shippingAddress)
    {
        try {
            $amastyQuote = $this->quoteRepository->get($shippingAddress->getQuoteId());
            if (!$amastyQuote->getData(QuoteInterface::SHIPPING_CAN_BE_MODIFIED)) {
                $shippingRate = $shippingAddress->getShippingRateByCode($shippingAddress->getShippingMethod());
                if ($shippingRate) {
                    $shippingAddress->setLimitCarrier($shippingRate->getCarrier());
                }
            }
        } catch (NoSuchEntityException $e) {
            null; //this is not amasty quote; do nothing
        }

        return null;
    }
}
