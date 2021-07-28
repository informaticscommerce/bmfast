<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */


namespace Amasty\Perm\Plugin;

use Amasty\Perm\Model\ResourceModel\DealerCustomer\CollectionFactory as DealerCustomerCollectionFactory;
use Amasty\Perm\Helper\Data as PermHelper;
use Magento\Sales\Model\ResourceModel\Order\Customer\Collection;

class OrderCustomerCollection
{
    /**
     * @var bool
     */
    protected $collectionModified = false;

    /**
     * @var PermHelper
     */
    protected $permHelper;

    /**
     * @var DealerCustomerCollectionFactory
     */
    protected $dealerCustomerCollectionFactory;

    public function __construct(
        DealerCustomerCollectionFactory $dealerCustomerCollectionFactory,
        PermHelper $permHelper
    ) {
        $this->dealerCustomerCollectionFactory = $dealerCustomerCollectionFactory;
        $this->permHelper = $permHelper;
    }

    /**
     * @param Collection $collection
     */
    public function beforeLoad(
        Collection $collection
    ) {
        if (!$collection->isLoaded() && !$this->collectionModified) {
            if ($this->permHelper->isBackendDealer() && !$this->isAllowAllOrders()) {
                $dealerCustomerCollection = $this->dealerCustomerCollectionFactory->create()
                    ->addFieldToFilter('dealer_id', $this->permHelper->getBackendDealer()->getId());

                $collection->addFieldToFilter(
                    'entity_id',
                    ['in' => $dealerCustomerCollection->getCustomersIds()]
                );
            }

            $this->collectionModified = true;
        }
    }

    /**
     * @return bool
     */
    private function isAllowAllOrders()
    {
        return $this->permHelper->isAllowAllCustomersAndOrders()
            && $this->permHelper->isAllowAllCustomersCreateOrders();
    }
}
