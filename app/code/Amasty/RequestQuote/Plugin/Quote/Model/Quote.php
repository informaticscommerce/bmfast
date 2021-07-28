<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Plugin\Quote\Model;

use Amasty\RequestQuote\Api\Data\QuoteInterface;
use Amasty\RequestQuote\Api\QuoteRepositoryInterface;
use Amasty\RequestQuote\Model\Source\Status;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Model\Quote as QuoteModel;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Quote\Model\Quote\Address;

class Quote
{
    /**
     * @var QuoteRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var State
     */
    private $appState;

    public function __construct(
        QuoteRepositoryInterface $quoteRepository,
        State $appState
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->appState = $appState;
    }

    /**
     * @param QuoteModel $subject
     * @param callable $proceed
     *
     * @return QuoteModel
     */
    public function aroundDelete($subject, $proceed)
    {
        //prevent clearing amasty quotes
        if (!$this->quoteRepository->isAmastyQuote((int) $subject->getId())) {
            $proceed();
        }

        return $subject;
    }

    /**
     * @param QuoteModel $subject
     * @param callable $proceed
     * @param CustomerInterface $customer
     * @param Address|null $billingAddress
     * @param Address|null $shippingAddress
     * @return QuoteModel
     */
    public function aroundAssignCustomerWithAddressChange(
        QuoteModel $subject,
        $proceed,
        CustomerInterface $customer,
        $billingAddress = null,
        $shippingAddress = null
    ) {
        if (!$this->isFrontend() || !$this->isAddressConfigured((int) $subject->getId())) {
            $proceed($customer, $billingAddress, $shippingAddress);
        }

        return $subject;
    }

    /**
     * @param QuoteModel $subject
     * @param $proceed
     * @param AddressInterface|null $address
     * @return QuoteModel
     */
    public function aroundSetShippingAddress(QuoteModel $subject, $proceed, $address)
    {
        if (!$this->isFrontend() || !$this->isAddressCantBeModified((int) $subject->getId())) {
            $proceed($address);
        }

        return $subject;
    }

    /**
     * @param QuoteModel $subject
     * @param $proceed
     * @param $addressId
     * @return QuoteModel
     */
    public function aroundRemoveAddress(QuoteModel $subject, $proceed, $addressId)
    {
        if (!$this->isFrontend() || !$this->isAddressCantBeModified((int) $subject->getId())) {
            $proceed($addressId);
        }

        return $subject;
    }

    /**
     * @param QuoteModel $subject
     * @param $proceed
     * @return QuoteModel
     */
    public function aroundRemoveAllAddresses(QuoteModel $subject, $proceed)
    {
        if (!$this->isFrontend() || !$this->isAddressCantBeModified((int) $subject->getId())) {
            $proceed();
        }

        return $subject;
    }

    /**
     * @param QuoteModel $subject
     * @param callable $proceed
     * @param $itemId
     * @return QuoteModel
     */
    public function aroundRemoveItem(QuoteModel $subject, callable $proceed, $itemId)
    {
        $item = $subject->getItemById($itemId);
        if (!$item || !$this->isFrontend() || $this->isItemCanBeDeleted($item)) {
            $proceed($itemId);
        }

        return $subject;
    }

    /**
     * @param QuoteItem $item
     * @return bool
     */
    private function isItemCanBeDeleted(QuoteItem $item)
    {
        $result = true;
        $quoteId = (int) $item->getQuoteId();
        if ($this->isAmastyQuote($quoteId)
            && $this->getAmastyQuote($quoteId)->getData(QuoteInterface::STATUS) != Status::CREATED
            && $item->getOptionByCode('amasty_quote_price')
        ) {
            $result = false;
        }

        return $result;
    }

    /**
     * @param int $quoteId
     * @return bool
     */
    private function isAddressCantBeModified(int $quoteId)
    {
        $result = false;
        if ($this->isAmastyQuote($quoteId)) {
            $amastyQuote = $this->quoteRepository->get($quoteId);
            $result = $amastyQuote->getData(QuoteInterface::SHIPPING_CONFIGURE)
                && !$amastyQuote->getData(QuoteInterface::SHIPPING_CAN_BE_MODIFIED);
        }

        return $result;
    }

    /**
     * @param int $quoteId
     * @return bool
     */
    private function isAddressConfigured(int $quoteId)
    {
        $result = false;
        if ($this->isAmastyQuote($quoteId)) {
            $amastyQuote = $this->quoteRepository->get($quoteId);
            $result = (bool) $amastyQuote->getData(QuoteInterface::SHIPPING_CONFIGURE);
        }

        return $result;
    }

    /**
     * @param int $quoteId
     * @return bool
     */
    private function isAmastyQuote(int $quoteId): bool
    {
        return $this->quoteRepository->isAmastyQuote($quoteId);
    }

    /**
     * @param int $quoteId
     * @return QuoteInterface
     */
    private function getAmastyQuote(int $quoteId): QuoteInterface
    {
        return $this->quoteRepository->get($quoteId);
    }

    /**
     * @return bool
     */
    private function isFrontend()
    {
        try {
            $result = in_array($this->appState->getAreaCode(), [
                Area::AREA_FRONTEND,
                Area::AREA_WEBAPI_REST,
                Area::AREA_WEBAPI_SOAP,
                'graphql'  // Area::AREA_GRAPHQL . 22x compatibility
            ]);
        } catch (LocalizedException $e) {
            $result = false;
        }

        return $result;
    }
}
