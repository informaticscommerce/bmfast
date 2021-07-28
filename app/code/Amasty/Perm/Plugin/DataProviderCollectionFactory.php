<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */


namespace Amasty\Perm\Plugin;

use Amasty\Perm\Helper\CollectionModifier;
use Amasty\Perm\Helper\Data as PermHelper;
use Amasty\Perm\Model\ResourceModel\DealerCustomer\CollectionFactory as DealerCustomerCollectionFactory;
use Amasty\Perm\Model\ResourceModel\DealerOrder\CollectionFactory as DealerOrderCollectionFactory;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory;

class DataProviderCollectionFactory
{
    protected $requestName;

    protected $collectionModifier;

    protected $permHelper;

    protected $dealerOrderCollectionFactory;

    protected $dealerCustomerCollectionFactory;

    protected $_scopeConfig;

    public function __construct(
        CollectionModifier $collectionModifier,
        PermHelper $permHelper,
        DealerCustomerCollectionFactory $dealerCustomerCollectionFactory,
        DealerOrderCollectionFactory $dealerOrderCollectionFactory
    ) {
        $this->collectionModifier = $collectionModifier;
        $this->permHelper = $permHelper;
        $this->dealerCustomerCollectionFactory = $dealerCustomerCollectionFactory;
        $this->dealerOrderCollectionFactory = $dealerOrderCollectionFactory;
    }

    /**
     * @param CollectionFactory $collectionFactory
     * @param $requestName
     * @return array
     */
    public function beforeGetReport(
        CollectionFactory $collectionFactory,
        $requestName
    ) {
        $this->requestName = $requestName;

        return [$requestName];
    }

    /**
     * @param CollectionFactory $collectionFactory
     * @param AbstractCollection $collection
     * @return AbstractCollection
     * @throws \Exception
     */
    public function afterGetReport(
        CollectionFactory $collectionFactory,
        $collection
    ) {
        if ($this->permHelper->isAllowAllCustomersAndOrders()
            || !$this->permHelper->isBackendDealer()
        ) {
            return $collection;
        }

        if ($this->collectionModifier->isOrderDataSource($this->requestName)) {
            $primaryKey = 'entity_id';
            $foreignKey = 'order_id';
            $dealerCollection = 'dealerOrderCollectionFactory';
        } elseif ($this->collectionModifier->isOrderRelatedDataSource($this->requestName)) {
            $primaryKey = 'order_id';
            $foreignKey = 'order_id';
            $dealerCollection = 'dealerOrderCollectionFactory';
        } elseif ($this->collectionModifier->isCustomerDataSource($this->requestName)) {
            $primaryKey = 'entity_id';
            $foreignKey = 'customer_id';
            $dealerCollection = 'dealerCustomerCollectionFactory';
        } elseif ($this->collectionModifier->isAmastyQuoteDataSource($this->requestName)) {
            $dealerCollection = 'dealerCustomerCollectionFactory';
            $this->collectionModifier->applyQuoteFilter(
                $this->permHelper->getBackendDealer()->getId(),
                $collection,
                $this->$dealerCollection
            );
        }

        if (isset($primaryKey)) {
            $this->collectionModifier->applyDealerFilter(
                $this->permHelper->getBackendDealer()->getId(),
                $collection,
                $this->$dealerCollection,
                $primaryKey,
                $foreignKey
            );
        }

        return $collection;
    }
}
