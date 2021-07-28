<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Plugin\Multishipping\Helper;

use Amasty\RequestQuote\Api\Data\QuoteInterface;
use Amasty\RequestQuote\Api\QuoteRepositoryInterface;
use Magento\Checkout\Model\Session;
use Magento\Multishipping\Helper\Data as MultishippingHelper;

class DataPlugin
{
    /**
     * @var QuoteRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var Session
     */
    private $checkoutSession;

    public function __construct(
        QuoteRepositoryInterface $quoteRepository,
        Session $checkoutSession
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param MultishippingHelper $subject
     * @param bool $result
     * @return bool
     */
    public function afterIsMultishippingCheckoutAvailable(MultishippingHelper $subject, bool $result): bool
    {
        if ($result && $this->quoteRepository->isAmastyQuote($this->getQuoteId())) {
            $result = (bool) $this->quoteRepository->get($this->getQuoteId())
                ->getData(QuoteInterface::SHIPPING_CAN_BE_MODIFIED);
        }

        return $result;
    }

    /**
     * @return int
     */
    private function getQuoteId(): int
    {
        return (int) $this->checkoutSession->getQuoteId();
    }
}
