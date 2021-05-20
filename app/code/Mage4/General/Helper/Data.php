<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mage4\General\Helper;

use Magento\Framework\Registry;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $objectManager;
    protected $storeManager;
    protected $messageManager;
    protected $registry;
    protected $configFactory;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        Registry $registry,
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $configFactory
    ) {
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
        $this->messageManager = $messageManager;
        $this->registry = $registry;
        $this->configFactory = $configFactory;
        
        parent::__construct($context);
    }

    public function getCustomerBySession() {
        $customer = $this->objectManager->get('Magento\Customer\Model\Session')->getCustomer();
        if($customer && $customer->getId()) {
            return $customer;
        }

        return NULL;
    }

    public function getCustomerAddressCollectionFilterByCompany($customer) {
        return $customer->getAddressesCollection()
            ->addFieldToFilter('company', ['neq' => 'NULL']);
    }

    public function loadCustomerAddressById($addressId) {
        return $this->objectManager->get('Magento\Customer\Model\AddressFactory')->create()->load($addressId);
    }

    public function getWishlistCollectionByCustomerId($customerId) {
        $wishlistItemCollection = $this->objectManager->get('Magento\Wishlist\Model\Wishlist')->loadByCustomerId($customerId, true)->getItemCollection();

        if($wishlistItemCollection->getSize()) {
            return $wishlistItemCollection;
        }

        return NULL;
    }

    public function getProductCollectionByIds($productIds) {
        $productCollection = $this->objectManager
            ->create('Magento\Catalog\Model\ResourceModel\Product\Collection')
            ->addAttributeToSelect('*')
            ->addFieldToFilter('entity_id', array('in' => $productIds));

        return $productCollection->load();
    }

    public function getObjectManager() {
        return $this->objectManager;
    }
}
