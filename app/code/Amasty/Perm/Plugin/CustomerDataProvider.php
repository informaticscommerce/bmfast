<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */

namespace Amasty\Perm\Plugin;

/**
 * Class CustomerDataProvider
 *
 * @author Artem Brunevski
 */

use Magento\Ui\DataProvider\AbstractDataProvider as AbstractDataProvider;
use Magento\Customer\Model\Customer\DataProvider as MagentoDataProvider;
use Magento\Customer\Model\Customer\DataProviderWithDefaultAddresses as DataProviderWithDefaultAddresses;
use Amasty\Perm\Model\Config\Source\Dealers as SourceDealers;
use Amasty\Perm\Helper\Data as PermHelper;
use Amasty\Perm\Model\DealerCustomerFactory;

class CustomerDataProvider
{
    /** @var  string */
    protected $_fieldSetName;

    /** @var SourceDealers  */
    protected $_sourceDealers;

    /** @var PermHelper  */
    protected $_permHelper;

    /** @var  DealerCustomerFactory*/
    protected $_dealerCustomerFactory;

    /**
     * @param SourceDealers $sourceDealers
     * @param PermHelper $permHelper
     * @param DealerCustomerFactory $dealerCustomerFactory
     */
    public function __construct(
        SourceDealers $sourceDealers,
        PermHelper $permHelper,
        DealerCustomerFactory $dealerCustomerFactory
    ) {
        $this->_sourceDealers = $sourceDealers;
        $this->_permHelper = $permHelper;
        $this->_dealerCustomerFactory = $dealerCustomerFactory;
    }

    /**
     * @param MagentoDataProvider | DataProviderWithDefaultAddresses $dataProvider
     * @param string $fieldSetName
     * @return array
     */
    public function beforeGetFieldsMetaInfo(
        AbstractDataProvider $dataProvider,
        $fieldSetName
    ) {
        $this->_fieldSetName = $fieldSetName;

        return [$fieldSetName];
    }

    /**
     * @param MagentoDataProvider | DataProviderWithDefaultAddresses $dataProvider
     * @param array $meta
     * @return array
     */
    public function afterGetFieldsMetaInfo(
        AbstractDataProvider $dataProvider,
        $meta
    ) {
        if ($this->_fieldSetName === 'customer') {
            $dealerId = $this->_permHelper->getBackendDealer()->getId();

            $meta['amasty_perm_dealer'] = [
                'visible' => ($dealerId === null), // show if not a dealer
                'options' => $this->_sourceDealers->toOptionArray(true)
            ];
        }

        return $meta;
    }

    /**
     * @param MagentoDataProvider | DataProviderWithDefaultAddresses $dataProvider
     * @param array $loadedData
     * @return array
     */
    public function afterGetData(
        AbstractDataProvider $dataProvider,
        $loadedData
    ) {
        if (is_array($loadedData)) {
            foreach ($loadedData as &$loadedItem) {
                if (is_array($loadedItem) &&
                    array_key_exists('customer', $loadedItem) &&
                    !array_key_exists('amasty_perm_dealer', $loadedItem['customer'])
                ) {
                    $dealer = $this->_dealerCustomerFactory->create()
                        ->load($loadedItem['customer']['entity_id'], 'customer_id')
                        ->getDealer();

                    $loadedItem['customer']['amasty_perm_dealer'] = $dealer->getId();
                }
            }
        }

        return $loadedData;
    }

    /**
     * @param MagentoDataProvider | DataProviderWithDefaultAddresses $dataProvider
     * @param array $meta
     * @return array
     */
    public function afterGetMeta(AbstractDataProvider $dataProvider, $meta)
    {
        if (array_key_exists('customer', $meta)) {
            $isVisible = !$this->_permHelper->isBackendDealer();

            $meta['customer']['children']['amasty_perm_dealer'] = [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'dataType' => 'number',
                            'formElement' => 'select',
                            'source' => 'customer',
                            'sortOrder' => 25,
                            'label' => __('Dealer'),
                            'visible' => $isVisible, // show if not a dealer
                            'options' => $this->_sourceDealers->toOptionArray(true),
                            'componentType' => 'field'
                        ]
                    ]
                ]
            ];
        }

        return $meta;
    }
}
