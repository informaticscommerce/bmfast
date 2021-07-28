<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */


namespace Amasty\Perm\Plugin;

use Amasty\Perm\Helper\CollectionModifier;
use Amasty\Perm\Helper\Data as PermHelper;
use Amasty\Perm\Model\ResourceModel\DealerOrder\CollectionFactory as DealerOrderCollectionFactory;

class BackendDashboardOrdersGrid
{
    protected $collectionModified = false;
    protected $permHelper;
    protected $dealerOrderCollectionFactory;
    protected $collectionModifier;

    public function __construct(
        DealerOrderCollectionFactory $dealerOrderCollectionFactory,
        PermHelper $permHelper,
        CollectionModifier $collectionModifier
    ) {
        $this->dealerOrderCollectionFactory = $dealerOrderCollectionFactory;
        $this->permHelper = $permHelper;
        $this->collectionModifier = $collectionModifier;
    }

    public function afterSetCollection(
        \Magento\Backend\Block\Dashboard\Orders\Grid $block
    ) {
        $collection = $block->getCollection();

        if (!$this->collectionModified) {
            if ($this->permHelper->isBackendDealer() && !$this->permHelper->isAllowAllCustomersCreateOrders()) {
                $this->collectionModifier->applyDealerFilter(
                    $this->permHelper->getBackendDealer()->getId(),
                    $collection,
                    $this->dealerOrderCollectionFactory,
                    'entity_id',
                    'order_id'
                );
            }

            $this->collectionModified = true;

            $block->setCollection($collection);
        }

        return $block;
    }
}
