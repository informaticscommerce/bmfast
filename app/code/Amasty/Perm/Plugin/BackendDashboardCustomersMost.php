<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */


namespace Amasty\Perm\Plugin;

use Amasty\Perm\Model\ResourceModel\DealerCustomer\CollectionFactory as DealerCustomerCollectionFactory;
use Amasty\Perm\Helper\Data as PermHelper;

class BackendDashboardCustomersMost
{
    protected $collectionModified = false;
    protected $permHelper;
    protected $dealerCustomerCollectionFactory;

    public function __construct(
        DealerCustomerCollectionFactory $dealerCustomerCollectionFactory,
        PermHelper $permHelper
    ) {
        $this->dealerCustomerCollectionFactory = $dealerCustomerCollectionFactory;
        $this->permHelper = $permHelper;
    }

    public function afterSetCollection(
        \Magento\Backend\Block\Dashboard\Tab\Customers\Most $block
    ) {
        $collection = $block->getCollection();

        if (!$this->collectionModified) {
            if ($this->permHelper->isBackendDealer() && !$this->permHelper->isAllowAllCustomersCreateOrders()) {
                $dealerCustomerCollection = $this->dealerCustomerCollectionFactory->create()
                    ->addFieldToFilter('dealer_id', $this->permHelper->getBackendDealer()->getId());

                $collection->addFieldToFilter('customer_id',
                    ['in' => $dealerCustomerCollection->getCustomersIds()]
                );
            }

            $this->collectionModified = true;

            $block->setCollection($collection);
        }

        return $block;
    }
}
