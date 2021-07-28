<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Plugin\Elasticsearch\Model\FieldMapper;

use Amasty\QuickOrder\Plugin\Elasticsearch\AdditionalFieldMapper;
use Magento\Elasticsearch\Elasticsearch5\Model\Adapter\FieldMapper\ProductFieldMapperProxy;

class ProductFieldMapperProxyPlugin extends AdditionalFieldMapper
{
    /**
     * @param ProductFieldMapperProxy $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetAllAttributesTypes(ProductFieldMapperProxy $subject, array $result): array
    {
        return $this->updateFields($result);
    }
}
