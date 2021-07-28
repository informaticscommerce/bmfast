<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Item;

use Amasty\QuickOrder\Model\Import\Provider\Bundle\Provider as BundleOption;
use Amasty\QuickOrder\Model\Import\Provider\Configurable\Provider as ConfigurableOption;
use Amasty\QuickOrder\Model\Import\Provider\CustomOption;
use Amasty\QuickOrder\Model\ItemConverter;
use Amasty\QuickOrder\Model\Session;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Locale\Format as LocaleFormat;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;

class ProductProvider
{
    /**
     * @var Session
     */
    private $sessionManager;

    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var LocaleFormat
     */
    private $localeFormat;

    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var array
     */
    private $optionCodes;

    /**
     * @var Pager
     */
    private $pager;

    /**
     * @var ItemConverter
     */
    private $itemConverter;

    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;

    public function __construct(
        ProductCollectionFactory $productCollectionFactory,
        Session $sessionManager,
        LocaleFormat $localeFormat,
        PriceCurrencyInterface $priceCurrency,
        StoreManagerInterface $storeManager,
        Pager $pager,
        ItemConverter $itemConverter,
        DataObjectFactory $dataObjectFactory
    ) {
        $this->sessionManager = $sessionManager;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->localeFormat = $localeFormat;
        $this->priceCurrency = $priceCurrency;
        $this->storeManager = $storeManager;
        $this->pager = $pager;
        $this->itemConverter = $itemConverter;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->init();
    }

    protected function init()
    {
        $this->optionCodes = [
            BundleOption::REQUEST_CODE,
            BundleOption::QTY_REQUEST_CODE,
            ConfigurableOption::REQUEST_CODE,
            CustomOption::REQUEST_CODE,
            'super_group'
        ];
    }

    /**
     * @param int $page
     * @return array
     */
    public function getProductsInfoByPage(int $page = 1): array
    {
        return array_merge(
            $this->getProductsInfo($this->pager->getItems($page)),
            [
                'current_page' => $page,
                'last_page' => $this->pager->getLastPage()
            ]
        );
    }

    /**
     * @return array
     */
    public function getAllProductsInfo(): array
    {
        return $this->getProductsInfo($this->pager->getAllItems());
    }

    /**
     * Retrieve products added in quick order grid via session
     *
     * @param array $itemsData
     * @return array
     */
    public function getProductsInfo(array $itemsData): array
    {
        $productsData = [];

        $productCollection = $this->getProductCollection();

        $notConfigured = $this->sessionManager->getNotConfigured();

        foreach ($itemsData as $itemData) {
            /** @var Product $product */
            if ($product = $productCollection->getItemById($itemData['product_id'] ?? 0)) {
                $itemId = (int) $itemData['id'];

                $product->setPreconfiguredValues(
                    $product->processBuyRequest($this->dataObjectFactory->create(['data' => $itemData]))
                );

                $stockStatus = $this->itemConverter->getStockStatus(
                    $this->itemConverter->resolveSimpleProduct($product, $itemData)
                );

                $productData = $this->itemConverter->convert($itemId, $product);
                $productData['qty'] = $itemData['qty'] ?? $productData['qty'];
                $productData['product_url'] = $itemData['product_url'] ?? $productData['product_url'];
                $productData['available_qty'] = $stockStatus->getQty();
                $productData['stock_status'] = $stockStatus->getStockStatus();

                foreach ($this->optionCodes as $optionCode) {
                    if (isset($itemData[$optionCode])) {
                        $productData[$optionCode] = $itemData[$optionCode];
                    }
                }

                if (isset($notConfigured[$itemId])) {
                    $errors = $notConfigured[$itemId];
                    $errors = explode("\n", $errors);
                    $errors = array_unique($errors);
                    $errors = array_values($errors);
                    $productData['errors'] = $errors;
                }

            } else {
                $productData = [];
            }

            $productsData[] = $productData;
        }

        return $productsData;
    }

    /**
     * @return ProductCollection
     */
    private function getProductCollection()
    {
        return $this->productCollectionFactory->create()
            ->setStoreId($this->getStore()->getId())
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('image')
            ->addAttributeToSelect('visibility')
            ->addAttributeToSelect('links_purchased_separately')
            ->addAttributeToSelect('links_title')
            ->addAttributeToSelect('allow_open_amount')
            ->addAttributeToSelect('giftcard_amounts')
            ->addAttributeToSelect('gift_message_available')
            ->addAttributeToSelect('giftcard_type')
            ->addAttributeToSelect('open_amount_min')
            ->addAttributeToSelect('open_amount_max')
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite()
            ->addIdFilter($this->sessionManager->getProductIds())
            ->addOptionsToResult();
    }

    /** @TODO: move price config into initializeion of widget */
    private function getPriceConfig()
    {
        return [
            'currencyFormat' => $this->getStore()->getCurrentCurrency()->getOutputFormat(),
            'priceFormat' => $this->localeFormat->getPriceFormat()
        ];
    }

    /**
     * @return StoreInterface
     */
    private function getStore()
    {
        return $this->storeManager->getStore();
    }
}
