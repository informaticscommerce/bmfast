<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Controller\Cart;

use Amasty\RequestQuote\Api\Data\QuoteItemInterface;
use Amasty\RequestQuote\Helper\Data;
use Amasty\RequestQuote\Model\RegistryConstants;
use Amasty\RequestQuote\Model\Source\Status;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\InputException;

class UpdatePost extends \Amasty\RequestQuote\Controller\Cart
{
    /**
     * @return void
     */
    protected function _emptyShoppingCart()
    {
        try {
            $this->cart->truncate()->save();
        } catch (\Magento\Framework\Exception\LocalizedException $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        } catch (\Exception $exception) {
            $this->messageManager->addExceptionMessage($exception, __('We can\'t update the shopping cart.'));
        }
    }

    /**
     * @return bool
     */
    protected function _updateShoppingCart()
    {
        $result = false;
        try {
            $quote = $this->getCheckoutSession()->getQuote();
            $remarks = $this->getRequest()->getParam('remarks', null);
            if ($remarks && trim($remarks)) {
                $quote->setRemarks($this->cartHelper->prepareCustomerNoteForSave($remarks));
            }
            $cartData = $this->getRequest()->getParam('cart');

            if (is_array($cartData)) {
                $quoteItems = [];
                foreach ($cartData as $index => &$data) {
                    if (isset($data['qty'])) {
                        $cartData[$index]['qty'] = $this->getLocateFilter()->filter(trim($data['qty']));
                    }
                    /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
                    $quoteItem = $quote->getItemById($index);
                    $quoteItems[] = $quoteItem;
                    if (!$quoteItem) {
                        throw new LocalizedException(__('Something went wrong'));
                    }

                    $price = isset($data['price'])
                        ? $this->convertPriceToBase($this->getLocateFilter()->filter(trim($data['price'])))
                        : $quoteItem->getProduct()->getFinalPrice();
                    if (!$this->getConfigHelper()->isAllowCustomizePrice()
                        && $this->getHidePriceProvider()->isHidePrice($quoteItem->getProduct())
                    ) {
                        $price = 0;
                    }
                    $data['price'] = $this->convertPriceToCurrent($price);
                    if (isset($data['qty']) && !$this->getHidePriceProvider()->isHidePrice($quoteItem->getProduct())) {
                        $productFinalPrice = $quoteItem->getProduct()->getFinalPrice(
                            $this->getLocateFilter()->filter(trim($data['qty']))
                        );
                        $price = min($productFinalPrice, $price);
                    }

                    $quoteItem->setCustomPrice($price);
                    $quoteItem->setOriginalCustomPrice($price);

                    if (isset($data['note'])) {
                        $quote->getItemById($index)->setAdditionalData(
                            $this->cartHelper->updateAdditionalData(
                                $quote->getItemById($index)->getAdditionalData(),
                                [QuoteItemInterface::CUSTOMER_NOTE_KEY => trim($data['note'])]
                            )
                        );
                    }
                }

                if (!$this->cart->getCustomerSession()->getCustomerId()
                    && $this->cart->getQuote()->getCustomerId()
                ) {
                    $this->cart->getQuote()->setCustomerId(null);
                }

                $cartData = $this->cart->suggestItemsQty($cartData);
                $this->cart->updateItems($cartData);
                $this->cart->getQuote()->collectTotals();

                foreach ($quoteItems as $quoteItem) {
                    $quoteItem->setAdditionalData(
                        $this->cartHelper->updateAdditionalData(
                            $quoteItem->getAdditionalData(),
                            [
                                QuoteItemInterface::REQUESTED_PRICE => $quoteItem->getPrice(),
                                QuoteItemInterface::CUSTOM_PRICE => $cartData[$quoteItem->getId()]['price'],
                                QuoteItemInterface::HIDE_ORIGINAL_PRICE => $this->getHidePriceProvider()->isHidePrice(
                                    $quoteItem->getProduct()
                                )
                            ]
                        )
                    );
                }

                $this->cart->save();
                $result = true;
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage(
                $this->getEscaper()->escapeHtml($e->getMessage())
            );
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('We can\'t update the shopping cart.'));
        }

        return $result;
    }

    /**
     * @param $price
     * @return float|int
     */
    private function convertPriceToBase($price)
    {
        $store = $this->getCheckoutSession()->getQuote()->getStore();
        $rate = $store->getBaseCurrency()->getRate(
            $this->priceCurrency->getCurrency($store)
        );
        if ($rate != 1) {
            $price = (float)$price / (float)$rate;
        }

        return $price;
    }

    /**
     * @param $price
     * @return float
     */
    private function convertPriceToCurrent($price)
    {
        return $this->priceCurrency->convert($price);
    }

    /**
     * @return void
     */
    protected function submitAction()
    {
        $quote = $this->checkoutSession->getQuote();
        /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
        foreach ($quote->getAllItems() as $quoteItem) {
            $priceOption = $this->dataObjectFactory->create(
                []
            )->setCode(
                'amasty_quote_price'
            )->setValue(
                $quoteItem->getPrice()
            )->setProduct(
                $quoteItem->getProduct()
            );
            $quoteItem->addOption($priceOption)->saveItemOptions();
        }
        $quote
            ->setSubmitedDate($this->getTimezone()->date()->format('Y-m-d H:i:s'))
            ->setStatus(Status::PENDING)
            ->save();
        $quote->setTotalsCollectedFlag(false)->collectTotals();
        $this->registry->register(RegistryConstants::AMASTY_QUOTE, $quote);
        $this->notifyCustomer();
        $this->notifyAdmin($quote->getId());
        $this->checkoutSession->setLastQuoteId($this->checkoutSession->getQuoteId());
        $this->checkoutSession->setQuoteId(null);

        $this->_eventManager->dispatch('amasty_request_quote_submit_after', ['quote' => $quote]);
    }

    /**
     * @return void
     */
    private function notifyCustomer()
    {
        $quote = $this->checkoutSession->getQuote();
        $quote['created_date_formatted'] = $quote->getCreatedAtFormatted(\IntlDateFormatter::MEDIUM);
        $this->emailSender->sendEmail(
            Data::CONFIG_PATH_CUSTOMER_SUBMIT_EMAIL,
            $this->getCustomerSession()->getCustomer()->getEmail(),
            [
                'viewUrl' => $this->_url->getUrl(
                    'amasty_quote/account/view',
                    ['quote_id' => $this->checkoutSession->getQuoteId()]
                ),
                'quote' => $quote,
                'remarks' => $this->cartHelper->retrieveCustomerNote($this->checkoutSession->getQuote()->getRemarks())
            ]
        );
    }

    /**
     * @param int $quoteId
     */
    private function notifyAdmin($quoteId)
    {
        if ($this->getConfigHelper()->isAdminNotificationsInstantly()) {
            $this->getAdminNotification()->sendNotification([$quoteId]);
        }
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $backUrl = null;

        $updateAction = (string)$this->getRequest()->getParam('update_cart_action');

        switch ($updateAction) {
            case 'empty_cart':
                $this->_emptyShoppingCart();
                break;
            case 'update_qty':
                $this->_updateShoppingCart();
                break;
            case 'submit':
                if ($this->_updateShoppingCart()) {
                    if (($email = $this->getRequest()->getParam('email', null))
                        && !$this->getConfigHelper()->isLoggedIn()
                    ) {
                        try {
                            $this->login();
                        } catch (LocalizedException $e) {
                            $this->messageManager->addErrorMessage($e->getMessage());
                            break;
                        } catch (\Exception $e) {
                            $this->messageManager->addErrorMessage(__('Something went wrong'));
                            $this->getLogger()->error($e->getMessage());
                            break;
                        }
                    }
                    $this->submitAction();
                    $backUrl = $this->_url->getUrl('amasty_quote/quote/success');
                }
                break;
            default:
                $this->_updateShoppingCart();
        }

        return $this->_goBack($backUrl);
    }

    /**
     * @throws LocalizedException
     * @throws InputException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function login()
    {
        $customer = $this->getCustomerExtractor()->extract('customer_account_create', $this->getRequest());
        /** @var CustomerInterface $customer */
        $customer = $this->getAccountManagement()->createAccount($customer);
        $this->_eventManager->dispatch(
            'customer_register_success',
            ['account_controller' => $this, 'customer' => $customer]
        );

        $confirmationStatus = $this->getAccountManagement()->getConfirmationStatus($customer->getId());
        if ($confirmationStatus === AccountManagementInterface::ACCOUNT_CONFIRMATION_REQUIRED) {
            $email = $this->getCustomerUrl()->getEmailConfirmationUrl($customer->getEmail());

            $this->messageManager->addSuccessMessage(
                __(
                    'You must confirm your account. Please check your email for the confirmation '
                    . 'link or <a href="%1">click here</a> for a new link.',
                    $email
                )
            );
        }
        if ($customer && $this->authenticate($customer)) {
            $this->refresh($customer);
            $this->checkoutSession->getQuote()->setCustomer($customer);
        }
    }

    /**
     * @param CustomerInterface $customer
     *
     * @return bool
     */
    private function authenticate($customer)
    {
        $customerId = $customer->getId();
        if ($this->getAuthentication()->isLocked($customerId)) {
            $this->messageManager->addErrorMessage(__('The account is locked.'));
            return false;
        }

        $this->getAuthentication()->unlock($customerId);
        $this->_eventManager->dispatch('customer_data_object_login', ['customer' => $customer]);

        return true;
    }

    /**
     * @param CustomerInterface $customer
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    private function refresh($customer)
    {
        if ($customer && $customer->getId()) {
            $this->_eventManager->dispatch('amquote_customer_authenticated');
            $this->getCustomerSession()->setCustomerDataAsLoggedIn($customer);
            $this->getCustomerSession()->regenerateId();
            $this->getCheckoutSession()->loadCustomerQuote();

            if ($this->getCookieManager()->getCookie('mage-cache-sessid')) {
                $metadata = $this->getCookieMetadataFactory()->createCookieMetadata();
                $metadata->setPath('/');
                $this->getCookieManager()->deleteCookie('mage-cache-sessid', $metadata);
            }
        }
    }
}
