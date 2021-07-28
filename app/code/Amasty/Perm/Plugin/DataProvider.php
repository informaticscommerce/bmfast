<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */


namespace Amasty\Perm\Plugin;

use Amasty\Perm\Helper\CollectionModifier;
use Amasty\Perm\Model\ResourceModel\DealerCustomer\CollectionFactory as DealerCustomerCollectionFactory;
use Amasty\Perm\Model\ResourceModel\DealerOrder\CollectionFactory as DealerOrderCollectionFactory;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider as UiComponentDataProvider;

class DataProvider extends DataProviderCollectionFactory
{
    const SPECIFIC_EXPORT_GRID_ACTIONS = [
        'mui_export_gridToXml',
        'mui_export_gridToCsv'
    ];

    /**
     * @var DealerCustomerCollectionFactory
     */
    protected $dealerCustomerCollectionFactory;

    /**
     * @var DealerOrderCollectionFactory
     */
    protected $dealerOrderCollectionFactory;

    /**
     * @var \Magento\Framework\Api\Filter
     */
    protected $dealersFilter;

    /**
     * @var CollectionModifier
     */
    protected $collectionModifier;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    public function __construct(
        DealerCustomerCollectionFactory $dealerCustomerCollectionFactory,
        DealerOrderCollectionFactory $dealerOrderCollectionFactory,
        CollectionModifier $collectionModifier,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->dealerCustomerCollectionFactory = $dealerCustomerCollectionFactory;
        $this->dealerOrderCollectionFactory = $dealerOrderCollectionFactory;
        $this->collectionModifier = $collectionModifier;
        $this->request = $request;
    }

    /**
     * @param UiComponentDataProvider $dataProvider
     * @param $data
     * @return mixed
     */
    public function afterGetData(UiComponentDataProvider $dataProvider, $data)
    {
        if ($this->collectionModifier->isCustomerDataSource($dataProvider->getName())) {
            $primaryKey = 'entity_id';
            $foreignKey = 'customer_id';
            $dealerCollection = 'dealerCustomerCollectionFactory';
        } elseif ($this->collectionModifier->isOrderDataSource($dataProvider->getName())) {
            $primaryKey = 'entity_id';
            $foreignKey = 'order_id';
            $dealerCollection = 'dealerOrderCollectionFactory';
        } elseif ($this->collectionModifier->isOrderRelatedDataSource($dataProvider->getName())) {
            $primaryKey = 'order_id';
            $foreignKey = 'order_id';
            $dealerCollection = 'dealerOrderCollectionFactory';
        }

        if (isset($primaryKey)) {
            $data = $this->addDealersData(
                $data,
                $this->$dealerCollection,
                $primaryKey,
                $foreignKey
            );
        }

        return $data;
    }

    /**
     * @param UiComponentDataProvider $dataProvider
     * @param \Closure $proceed
     * @param \Magento\Framework\Api\Filter $filter
     */
    public function aroundAddFilter(
        UiComponentDataProvider $dataProvider,
        \Closure $proceed,
        \Magento\Framework\Api\Filter $filter
    ) {
        $ret = null;

        if ($filter->getField() === CollectionModifier::DEALERS_COLUMN
            && ($this->collectionModifier->isCustomerDataSource($dataProvider->getName())
                || $this->collectionModifier->isOrderDataSource($dataProvider->getName())
                || $this->collectionModifier->isOrderRelatedDataSource($dataProvider->getName())
        )) {
            $this->dealersFilter = $filter;
        } else {
            $ret = $proceed($filter);
        }
    }

    /**
     * @param UiComponentDataProvider $dataProvider
     * @param AbstractCollection $collection
     * @return $collection
     */
    public function afterGetSearchResult(
        \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider $dataProvider,
        $collection
    ) {
        $isCustomerDataSource = false;

        if ($this->collectionModifier->isOrderDataSource($dataProvider->getName())) {
            $primaryKey = 'entity_id';
            $foreignKey = 'order_id';
            $dealerCollection = 'dealerOrderCollectionFactory';
        } elseif ($this->collectionModifier->isOrderRelatedDataSource($dataProvider->getName())) {
            $primaryKey = 'order_id';
            $foreignKey = 'order_id';
            $dealerCollection = 'dealerOrderCollectionFactory';
        } elseif ($this->collectionModifier->isCustomerDataSource($dataProvider->getName())) {
            $primaryKey = 'entity_id';
            $foreignKey = 'customer_id';
            $dealerCollection = 'dealerCustomerCollectionFactory';
            $isCustomerDataSource = true;
        }

        if (isset($primaryKey)) {
            $this->addDealersDataWithExport(
                $collection,
                $this->$dealerCollection,
                $primaryKey,
                $foreignKey,
                $isCustomerDataSource
            );
        }

        return $collection;
    }

    /**
     * @param $collection
     * @param $factory
     * @param string $primaryKey
     * @param string $foreignKey
     */
    protected function addDealersFilter(
        $collection,
        $factory,
        $primaryKey = 'entity_id',
        $foreignKey = 'entity_id'
    ) {
        if ($this->dealersFilter !== null) {
            $this->collectionModifier->applyDealerFilter(
                $this->dealersFilter->getValue(),
                $collection,
                $factory,
                $primaryKey,
                $foreignKey
            );
        }
    }

    /**
     * @param $data
     * @param $factory
     * @param string $primaryKey
     * @param string $foreignKey
     * @return mixed
     */
    protected function addDealersData(
        $data,
        $factory,
        $primaryKey = 'entity_id',
        $foreignKey = 'entity_id'
    ) {
        if (array_key_exists('items', $data)) {
            $ids = [];
            $items = [];

            foreach ($data['items'] as &$item) {
                $ids[] = $item[$primaryKey];
                $items[$item[$primaryKey]] = &$item;
            }

            $collection = $factory->create()->addDealersToSelect($ids);

            foreach ($collection as $object) {
                if (array_key_exists($object->getData($foreignKey), $items)) {
                    $item = &$items[$object->getData($foreignKey)];

                    if (!array_key_exists(CollectionModifier::DEALERS_COLUMN, $item)) {
                        $item[CollectionModifier::DEALERS_COLUMN] = [];
                    }

                    $item[CollectionModifier::DEALERS_COLUMN][] = $object->getContactname();
                }
            }
        }

        return $data;
    }

    protected function addDealersDataWithExport(
        $collection,
        $factory,
        $primaryKey = 'entity_id',
        $foreignKey = 'entity_id',
        $isCustomerDataSource = false
    ) {
        $fullActionName = $this->request->getFullActionName();

        if (in_array($fullActionName, self::SPECIFIC_EXPORT_GRID_ACTIONS)) {
            if ($isCustomerDataSource) {
                $collection = $this->addDealersDataToCustomerExport($collection);
            } else {
                $collection = $this->addDealersDataToExport($collection, $primaryKey, $foreignKey);
            }
        } else {
            $this->addDealersFilter($collection, $factory, $primaryKey, $foreignKey);
        }

        return $collection;
    }

    protected function addDealersDataToExport(
        $collection,
        $primaryKey = 'entity_id',
        $foreignKey = 'entity_id'
    ) {
        if (!array_key_exists('amdealerorder', $collection->getSelect()->getPart('from'))) {
            $db = $collection->getConnection();
            $collection->getSelect()->joinLeft(
                ['amdealerorder' => $collection->getTable('amasty_perm_dealer_order')],
                $db->quoteIdentifier('main_table.' . $primaryKey) .
                    ' = ' . $db->quoteIdentifier('amdealerorder.' . $foreignKey),
                ['main_table.*', CollectionModifier::DEALERS_COLUMN => 'amdealerorder.contactname']
            );
            $collection->load();
        }

        return $collection;
    }

    protected function addDealersDataToCustomerExport($collection)
    {
        if (!array_key_exists('adc', $collection->getSelect()->getPart('from'))) {
            $collection->getSelect()->joinLeft(
                ['adc' => $collection->getTable('amasty_perm_dealer_customer')],
                'main_table.entity_id = adc.`customer_id`'
            )->joinLeft(
                ['ad' => $collection->getTable('amasty_perm_dealer')],
                'ad.entity_id = adc.dealer_id'
            )->joinLeft(
                ['au' => $collection->getTable('admin_user')],
                'au.user_id = ad.user_id',
                ['main_table.*', 'CONCAT_WS(" ", au.firstname, au.lastname) AS ' . CollectionModifier::DEALERS_COLUMN]
            );
            $collection->load();
        }

        return $collection;
    }
}
