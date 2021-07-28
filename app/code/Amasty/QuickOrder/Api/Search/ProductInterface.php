<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


namespace Amasty\QuickOrder\Api\Search;

interface ProductInterface
{
    const ID = 'id';
    const SKU = 'sku';
    const NAME = 'name';
    const PRICE = 'price';
    const IMAGE = 'image';
    const TYPE_ID = 'type_id';

    /**
     * Product id
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set product id
     *
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * Product sku
     *
     * @return string
     */
    public function getSku();

    /**
     * Set product sku
     *
     * @param string $sku
     * @return $this
     */
    public function setSku($sku);

    /**
     * Product name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set product name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * Product price
     *
     * @return string|null
     */
    public function getPrice();

    /**
     * Set product price
     *
     * @param string $price
     * @return $this
     */
    public function setPrice($price);

    /**
     * Product image
     *
     * @return string|null
     */
    public function getImage();

    /**
     * Set product image
     *
     * @param string $image
     * @return $this
     */
    public function setImage($image);

    /**
     * Product type
     *
     * @return string
     */
    public function getTypeId(): string;

    /**
     * Set product type
     *
     * @param string $typeId
     * @return $this
     */
    public function setTypeId(string $typeId);
}
