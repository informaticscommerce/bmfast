<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Model\Quote\Backend\Carrier;

use Amasty\RequestQuote\Api\Data\QuoteInterface;
use Amasty\RequestQuote\Api\QuoteRepositoryInterface;
use Amasty\RequestQuote\Helper\Data;
use Amasty\RequestQuote\Model\Quote\Backend\Edit;
use Amasty\RequestQuote\Model\Quote\Backend\Session;
use Amasty\RequestQuote\Model\Quote\Carrier\Custom as CustomCarrier;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Rate\ResultFactory as RateResultFactory;
use Psr\Log\LoggerInterface;

class Custom extends CustomCarrier
{
    const BACKEND_ROUTE_NAME = 'amasty_quote';

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Edit
     */
    private $editModel;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    public function __construct(
        Session $session,
        Edit $editModel,
        RequestInterface $request,
        QuoteRepositoryInterface $quoteRepository,
        Data $configHelper,
        RateResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        PriceCurrencyInterface $priceCurrency,
        LoggerInterface $logger,
        array $data = []
    ) {
        parent::__construct(
            $quoteRepository,
            $configHelper,
            $rateResultFactory,
            $rateMethodFactory,
            $scopeConfig,
            $rateErrorFactory,
            $logger,
            $data
        );
        $this->session = $session;
        $this->editModel = $editModel;
        $this->request = $request;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @param RateRequest $request
     * @return float
     */
    protected function getCustomFee(RateRequest $request): float
    {
        if ($this->editModel->hasData(QuoteInterface::CUSTOM_FEE)) {
            $result = (float) $this->editModel->getData(QuoteInterface::CUSTOM_FEE);
        } elseif ($this->session->getQuote()->hasData(QuoteInterface::CUSTOM_FEE)) {
            $result = (float) $this->session->getQuote()->getData(QuoteInterface::CUSTOM_FEE);
        } elseif ($this->session->getParentQuote()->hasData(QuoteInterface::CUSTOM_FEE)) {
            $result = (float) $this->session->getParentQuote()->getData(QuoteInterface::CUSTOM_FEE);
        } else {
            $result = 0;
        }

        return $this->convertPriceToBase($result);
    }

    /**
     * @param RateRequest $request
     * @return bool
     */
    protected function isAvailable(RateRequest $request): bool
    {
        return $this->request->getModuleName() === self::BACKEND_ROUTE_NAME;
    }

    /**
     * @return int
     */
    protected function getStoreId()
    {
        return (int) $this->session->getStoreId();
    }

    /**
     * @param $price
     * @return float|int
     */
    private function convertPriceToBase($price)
    {
        $store = $this->session->getQuote()->getStore();
        $rate = $store->getBaseCurrency()->getRate(
            $this->priceCurrency->getCurrency($store)
        );
        if ($rate != 1) {
            $price = (float)$price / (float)$rate;
        }

        return $price;
    }
}
