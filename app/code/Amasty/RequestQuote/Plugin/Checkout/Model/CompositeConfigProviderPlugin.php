<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Plugin\Checkout\Model;

use Amasty\RequestQuote\Api\Data\QuoteInterface;
use Amasty\RequestQuote\Api\QuoteRepositoryInterface;
use Magento\Checkout\Model\CompositeConfigProvider;
use Magento\Checkout\Model\Session as CheckoutSession;

class CompositeConfigProviderPlugin
{
    /**
     * @var QuoteRepositoryInterface
     */
    private $quoteRepository;
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    public function __construct(
        QuoteRepositoryInterface $quoteRepository,
        CheckoutSession $checkoutSession
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param CompositeConfigProvider $subject
     * @param array $config
     * @return array
     */
    public function afterGetConfig(CompositeConfigProvider $subject, array $config): array
    {
        if ($this->quoteRepository->isAmastyQuote((int) $this->checkoutSession->getQuoteId())) {
            $amastyQuote = $this->quoteRepository->get($this->checkoutSession->getQuoteId());
            if ($amastyQuote->getData(QuoteInterface::SHIPPING_CONFIGURE)
                && !$amastyQuote->getData(QuoteInterface::SHIPPING_CAN_BE_MODIFIED)
                && isset($config['customerData']['addresses'])
            ) {
                $config['customerData']['addresses'] = [];
            }
        }

        return $config;
    }
}
