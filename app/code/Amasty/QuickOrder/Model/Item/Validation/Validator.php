<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Item\Validation;

use Amasty\QuickOrder\Model\ResourceModel\Inventory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type\AbstractType;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Phrase;
use Magento\Store\Model\StoreManagerInterface;

class Validator
{
    const SUCCESS = 0;

    const NOT_CONFIGURED = 1;

    const ERROR = 2;

    /**
     * @var array|null
     */
    private $stockInfo;

    /**
     * @var Inventory
     */
    private $inventory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Result
     */
    private $result;

    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var ProductCollection
     */
    private $productCollection;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        Inventory $inventory,
        StoreManagerInterface $storeManager,
        Result $result,
        ProductCollectionFactory $productCollectionFactory,
        ProductRepositoryInterface $productRepository
    ) {
        $this->inventory = $inventory;
        $this->storeManager = $storeManager;
        $this->result = $result;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * @param array $values
     * @param string $field
     */
    public function init(array $values, string $field)
    {
        $this->stockInfo = $this->getStockInfo($values, $field);
        $this->productCollection = $this->productCollectionFactory->create()
            ->setFlag('has_stock_status_filter', true)
            ->addFieldToFilter(
                $field,
                $values
            )
            ->addOptionsToResult();
    }

    /**
     * Returning validation object with code , message , product id
     *
     * @param array $itemData
     *
     * @return Result
     */
    public function validate(array $itemData): Result
    {
        $this->result->setStatusCode(self::SUCCESS);
        $this->result->setMessage('');

        $productSku = $itemData['sku'] ?? '';

        /** @var Product $product */
        $product = $this->getProduct($productSku);
        if (!$product) {
            return $this->getErrorResponse(__('Product doesn\'t exist'));
        }

        if ($product->getStatus()) {
            if ($this->getStockStatus($productSku)) {
                $cartCandidates = $product->getTypeInstance()->prepareForCartAdvanced(
                    new DataObject($itemData),
                    $product,
                    AbstractType::PROCESS_MODE_FULL
                );

                /**
                 * Error message
                 */
                if (is_string($cartCandidates) || $cartCandidates instanceof Phrase) {
                    if ($cartCandidates instanceof Phrase) {
                        $cartCandidates = $cartCandidates->render();
                    }

                    $this->result->setStatusCode(self::NOT_CONFIGURED);
                    $errors = explode("\n", $cartCandidates);
                    $error = reset($errors);
                    $this->result->setMessage($error);
                }

                $this->result->setProductId($product->getId());
                $this->result->setProductData([
                    'product_name' => $product->getName(),
                    'product_url' => $product->getProductUrl()
                ]);
            } else {
                $this->getErrorResponse(__('Out of stock'));
            }
        } else {
            $this->getErrorResponse(__('Disabled'));
        }

        return $this->result;
    }

    /**
     * @param string $sku
     *
     * @return \Magento\Catalog\Api\Data\ProductInterface|null
     */
    protected function getProduct(string $sku)
    {
        if ($this->productCollection) {
            $product = $this->productCollection->getItemByColumnValue('sku', $sku);
        } else {
            try {
                $product = $this->productRepository->get($sku);
            } catch (NoSuchEntityException $ex) {
                $product = null;
            }
        }

        return $product;
    }

    /**
     * @param Phrase $message
     *
     * @return Result
     */
    private function getErrorResponse(Phrase $message)
    {
        $this->result->setStatusCode(self::ERROR);
        $this->result->setMessage($message->render());
        $this->result->setProductId(null);

        return $this->result;
    }

    /**
     * @param string $productSku
     * @return int
     */
    private function getStockStatus(string $productSku): int
    {
        return (int) ($this->stockInfo[$productSku]
            ?? $this->getStockInfo([$productSku], 'sku')[$productSku]
            ?? 0);
    }

    /**
     * @param array $values
     * @param string $field
     * @return array
     */
    private function getStockInfo(array $values, string $field): array
    {
        return $this->inventory->getStockInfo(
            $values,
            $field,
            $this->storeManager->getStore()->getWebsite()->getCode()
        );
    }
}
