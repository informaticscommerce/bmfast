<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Model\CheckoutConfigProvider;

use Amasty\RequestQuote\Api\Data\QuoteInterface;
use Amasty\RequestQuote\Api\QuoteRepositoryInterface;
use Amasty\RequestQuote\Model\Customer\Address\CustomerAddressDataFormatterFactory;
use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class ShippingAddress implements ConfigProviderInterface
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var CustomerAddressDataFormatterFactory
     */
    private $customerAddressDataFormatterFactory;

    /**
     * @var QuoteRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        QuoteRepositoryInterface $quoteRepository,
        CheckoutSession $checkoutSession,
        CustomerAddressDataFormatterFactory $customerAddressDataFormatterFactory,
        LoggerInterface $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->customerAddressDataFormatterFactory = $customerAddressDataFormatterFactory;
        $this->quoteRepository = $quoteRepository;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): array
    {
        $config = [];
        try {
            if ($this->quoteRepository->isAmastyQuote((int) $this->checkoutSession->getQuoteId())) {
                $amastyQuote = $this->quoteRepository->get($this->checkoutSession->getQuoteId());
                if ($amastyQuote->getData(QuoteInterface::SHIPPING_CONFIGURE)) {
                    $config = [
                        'amasty_quote' => [
                            'shipping_address' => $this->getShippingAddress()
                        ]
                    ];
                }
            }
        } catch (NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }

        return $config;
    }

    /**
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function getShippingAddress(): array
    {
        $shippingAddress = $this->customerAddressDataFormatterFactory->create()->prepareAddress(
            $this->checkoutSession->getQuote()->getShippingAddress()->exportCustomerAddress()
        );
        unset($shippingAddress['region']);

        return $shippingAddress;
    }
}
