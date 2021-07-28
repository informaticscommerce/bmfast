<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */


namespace Amasty\Perm\Helper;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class CollectionModifier
{
    const CUSTOMER_DATA_SOURCE = ['customer_listing_data_source'];
    const ORDER_DATA_SOURCE = ['sales_order_grid_data_source'];
    const AMASTY_QUOTE_DATA_SOURCE = ['amasty_quote_grid_data_source'];
    const ORDER_RELATED_DATA_SOURCE = [
        'sales_order_invoice_grid_data_source',
        'sales_order_shipment_grid_data_source',
        'sales_order_creditmemo_grid_data_source',
        'amrma_request_listing_data_source'
    ];

    const DEALERS_COLUMN = 'amasty_perm_dealers';

    /**
     * @param $dataSource
     * @return bool
     */
    public function isCustomerDataSource($dataSource)
    {
        return in_array($dataSource, self::CUSTOMER_DATA_SOURCE);
    }

    /**
     * @param $dataSource
     * @return bool
     */
    public function isAmastyQuoteDataSource($dataSource)
    {
        return in_array($dataSource, self::AMASTY_QUOTE_DATA_SOURCE);
    }

    /**
     * @param $dataSource
     * @return bool
     */
    public function isOrderDataSource($dataSource)
    {
        return in_array($dataSource, self::ORDER_DATA_SOURCE);
    }

    /**
     * @param $dataSource
     * @return bool
     */
    public function isOrderRelatedDataSource($dataSource)
    {
        return in_array($dataSource, self::ORDER_RELATED_DATA_SOURCE);
    }

    /**
     * Filter collection by dealer
     * @param $value
     * @param AbstractCollection $collection
     * @param $factory
     * @param string $primaryKey
     * @param string $foreignKey
     * @param string $filterPostfix
     * @throws \Zend_Db_Select_Exception
     */
    public function applyDealerFilter(
        $value,
        AbstractCollection $collection,
        $factory,
        $primaryKey = 'entity_id',
        $foreignKey = 'entity_id',
        $filterPostfix = '_filter'
    ) {
        $filterCollection = $factory->create()
            ->addDealersToSelect([])
            ->addFieldToFilter('dealer_id', ['eq' => $value]);

        $db = $collection->getConnection();
        // phpcs:ignore
        $idsSelect = 'SELECT DISTINCT ' . $db->quoteIdentifier($foreignKey)
            . ' FROM (' . $filterCollection->getSelect()->__toString() . ') AS tmp';
        $from = $collection->getSelect()->getPart(\Zend_Db_Select::FROM);
        $fkAlias = self::DEALERS_COLUMN . $filterPostfix;

        $from[$fkAlias] = [
            'joinType' => 'inner join',
            'schema' => null,
            'tableName' => new \Zend_Db_Expr('(' . $idsSelect . ')'),
            'joinCondition' => $db->quoteIdentifier('main_table.' . $primaryKey) .
                ' = ' . $db->quoteIdentifier($fkAlias . '.' . $foreignKey)
        ];

        $collection->getSelect()->setPart(\Zend_Db_Select::FROM, $from);
    }

    /**
     * @param $dealerId
     * @param $collection
     * @param $factory
     */
    public function applyQuoteFilter($dealerId, $collection, $factory)
    {
        $dealerCustomerCollection = $factory->create()
            ->addFieldToFilter('dealer_id', $dealerId);
        $collection->addFieldToFilter(
            'customer_id',
            ['in' => $dealerCustomerCollection->getCustomersIds()]
        );
    }
}
