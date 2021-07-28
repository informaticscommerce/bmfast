<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */

namespace Amasty\Perm\Helper;

use Amasty\Perm\Model\ResourceModel\DealerCustomer\CollectionFactory as DealerCustomerCollectionFactory;
use Amasty\Perm\Model\ResourceModel\DealerOrder\CollectionFactory as DealerOrderCollectionFactory;
use Amasty\Perm\Model\ResourceModel\Dealer\CollectionFactory as DealerCollectionFactory;
use Magento\Sales\Model\Order;
use Amasty\Perm\Model\Dealer;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const SCOPE_GENERAL_SINGLE_DEALER = 'amasty_perm/general/single_dealer';
    const SCOPE_GENERAL_SEND_EMAIL = 'amasty_perm/general/send_email';
    const SCOPE_GENERAL_DEFAULT_DEALER = 'amasty_perm/general/default_dealer';
    const SCOPE_GENERAL_REASSIGN_FIELDS = 'amasty_perm/general/reassign_fields';
    const SCOPE_GENERAL_FROM_TO = 'amasty_perm/general/from_to';
    const SCOPE_GENERAL_AUTHOR = 'amasty_perm/general/author';
    const SCOPE_GENERAL_EDIT_NO_GRID = 'amasty_perm/general/edit_no_grid';
    const SCOPE_GENERAL_ALLOW_ALL_CUSTOMERS_AND_ORDERS = 'amasty_perm/general/allow_all_customers_and_orders';
    const SCOPE_GENERAL_ALLOW_ALL_CUSTOMERS_CREATE_ORDERS = 'amasty_perm/general/allow_all_customers_create_orders';

    const SCOPE_FRONTEND_ON_REGISTRATION = 'amasty_perm/frontend/on_registration';
    const SCOPE_FRONTEND_IN_ACCOUNT = 'amasty_perm/frontend/in_account';
    const SCOPE_FRONTEND_DESCRIPTION_CHECKOUT = 'amasty_perm/frontend/description_checkout';
    
    const FROM_USER_EDIT = 'from_user_edit';

    protected $scopeConfig;
    protected $singleDealerMode;
    protected $sendEmailMode;
    protected $reassignFieldsMode;
    protected $fromToMode;
    protected $authorMode;
    protected $editNoGridMode;
    protected $isOnRegistrationMode;
    protected $isInAccountMode;
    protected $isDescriptionCheckout;
    protected $authSession;
    protected $backendDealer;
    protected $dealers;
    protected $dealerFactory;
    protected $dealerCustomerCollectionFactory;
    protected $dealerOrderCollectionFactory;
    protected $dealerCollectionFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Amasty\Perm\Model\DealerFactory $dealerFactory,
        DealerCustomerCollectionFactory $dealerCustomerCollectionFactory,
        DealerOrderCollectionFactory $dealerOrderCollectionFactory,
        DealerCollectionFactory $dealerCollectionFactory
    ) {
        parent::__construct($context);
        $this->scopeConfig = $context->getScopeConfig();
        $this->authSession = $authSession;
        $this->dealerFactory = $dealerFactory;
        $this->dealerCustomerCollectionFactory = $dealerCustomerCollectionFactory;
        $this->dealerOrderCollectionFactory = $dealerOrderCollectionFactory;
        $this->dealerCollectionFactory = $dealerCollectionFactory;
    }

    public function getScopeValue($path)
    {
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function isSingleDealerMode()
    {
        if ($this->singleDealerMode === null) {
            $this->singleDealerMode = $this->getScopeValue(self::SCOPE_GENERAL_SINGLE_DEALER) === '1';
        }
        
        return $this->singleDealerMode;
    }

    public function isSendEmailMode()
    {
        if ($this->sendEmailMode === null) {
            $this->sendEmailMode = $this->getScopeValue(self::SCOPE_GENERAL_SEND_EMAIL) === '1';
        }
        
        return $this->sendEmailMode;
    }

    public function isReassignFieldsMode()
    {
        if ($this->reassignFieldsMode === null) {
            $this->reassignFieldsMode = $this->getScopeValue(self::SCOPE_GENERAL_REASSIGN_FIELDS) === '1';
        }
        
        return $this->reassignFieldsMode;
    }

    public function isFromToMode()
    {
        if ($this->fromToMode === null) {
            $this->fromToMode = $this->getScopeValue(self::SCOPE_GENERAL_FROM_TO) === '1';
        }
        
        return $this->fromToMode;
    }

    public function isAuthorMode()
    {
        if ($this->authorMode === null) {
            $this->authorMode = $this->getScopeValue(self::SCOPE_GENERAL_AUTHOR) === '1';
        }
        
        return $this->authorMode;
    }

    public function isEditNoGridMode()
    {
        if ($this->editNoGridMode === null) {
            $this->editNoGridMode = $this->getScopeValue(self::SCOPE_GENERAL_EDIT_NO_GRID) === '1';
        }
        
        return $this->editNoGridMode;
    }

    public function isOnRegistrationMode()
    {
        if ($this->isOnRegistrationMode === null) {
            $this->isOnRegistrationMode = $this->getScopeValue(self::SCOPE_FRONTEND_ON_REGISTRATION) === '1';
        }
        
        return $this->isOnRegistrationMode;
    }

    public function isInAccountMode()
    {
        if ($this->isInAccountMode === null) {
            $this->isInAccountMode = $this->getScopeValue(self::SCOPE_FRONTEND_IN_ACCOUNT) === '1';
        }
        
        return $this->isInAccountMode;
    }

    public function isDescriptionCheckoutMode()
    {
        if ($this->isDescriptionCheckout === null) {
            $this->isDescriptionCheckout = $this->getScopeValue(self::SCOPE_FRONTEND_DESCRIPTION_CHECKOUT) === '1';
        }
        
        return $this->isDescriptionCheckout;
    }

    public function isBackendDealer()
    {
        return $this->getBackendDealer() !== null && $this->getBackendDealer()->checkPermissions();
    }

    public function isAllowAllCustomersAndOrders()
    {
        return $this->getScopeValue(self::SCOPE_GENERAL_ALLOW_ALL_CUSTOMERS_AND_ORDERS);
    }

    public function isAllowAllCustomersCreateOrders()
    {
        return $this->getScopeValue(self::SCOPE_GENERAL_ALLOW_ALL_CUSTOMERS_CREATE_ORDERS);
    }

    /**
     * @return Dealer
     */
    public function getBackendDealer()
    {
        if ($this->backendDealer === null) {
            $user = $this->authSession->getUser();
            if ($user) {
                $this->backendDealer = $this->dealerFactory->create()
                    ->load($user->getId(), 'user_id');
            }
        }

        return $this->backendDealer;
    }

    public function hasDealers()
    {
        $dealers = $this->getDealers();
        
        return count($dealers) > 0;
    }

    public function loadDealers(Order $order)
    {
        if ($this->dealers === null) {
            $this->dealers = [];

            if ($this->isBackendDealer()) {
                $this->fillBackendDealers();
            } elseif ($order->getCustomerId()) {
                $this->fillFrontendDealers($order->getCustomerId());
            }

            if (count($this->dealers) === 0) {
                $this->fillDefaultDealers();
            }
        }

        return $this->dealers;
    }

    public function getDealers()
    {
        return $this->dealers !== null ? $this->dealers : [];
    }

    protected function fillDefaultDealers()
    {
        $dealerId = $this->getScopeValue(self::SCOPE_GENERAL_DEFAULT_DEALER);

        if ($dealerId > 0) {
            $dealer = $this->dealerFactory->create()->load($dealerId);

            if ($dealer->checkPermissions()) {
                $this->dealers[] = $dealer;
            }
        }
    }

    protected function fillBackendDealers()
    {
        $dealer = $this->getBackendDealer();

        if ($dealer->checkPermissions()) {
            $this->dealers[] = $dealer;
        }
    }

    protected function fillFrontendDealers($customerId)
    {
        $dealerCustomerCollection = $this->dealerCustomerCollectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId);
        $dealersIds = $dealerCustomerCollection->getDealersIds();

        if (count($dealersIds) > 0) {
            $dealerCollection = $this->dealerCollectionFactory->create()
                ->addUserData()
                ->addFieldToFilter('main_table.entity_id', ['in' => $dealersIds]);

            foreach ($dealerCollection as $dealer) {
                if ($dealer->checkPermissions()) {
                    $this->dealers[] = $dealer;
                }
            }
        }
    }

    protected function checkPermissionsByDealersIds(array $dealersIds)
    {
        if (!in_array($this->getBackendDealer()->getId(), $dealersIds)) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(
                __(
                    "%1 don't have permissions for order",
                    $this->getBackendDealer()->getContactname()
                )
            );
        }
    }

    public function checkPermissionsByOrder(Order $order)
    {
        if (!$this->isAllowAllCustomersAndOrders() && $this->isBackendDealer()) {
            $dealerOrderCollection = $this->dealerOrderCollectionFactory->create()
                ->addFieldToFilter('order_id', $order->getId());
            $dealersIds = $dealerOrderCollection->getDealersIds();
            $this->checkPermissionsByDealersIds($dealersIds);
        }
    }

    public function checkPermissionsByCustomerId($customerId)
    {
        if ($this->isBackendDealer() && !$this->isAllowAllCustomersAndOrders()) {
            $dealerCustomerCollection = $this->dealerCustomerCollectionFactory->create()
                ->addFieldToFilter('customer_id', $customerId);
            $dealersIds = $dealerCustomerCollection->getDealersIds();
            $this->checkPermissionsByDealersIds($dealersIds);
        }
    }
}
