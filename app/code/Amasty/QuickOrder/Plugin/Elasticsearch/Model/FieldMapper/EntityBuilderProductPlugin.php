<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Plugin\Elasticsearch\Model\FieldMapper;

use Amasty\ElasticSearch\Model\Indexer\Structure\EntityBuilder\Product as EntityBuilderProduct;
use Amasty\QuickOrder\Plugin\Elasticsearch\AdditionalFieldMapper;

class EntityBuilderProductPlugin extends AdditionalFieldMapper
{
    /**
     * @phpstan-ignore-next-line
     *
     * @param EntityBuilderProduct $subject
     * @param array $result
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterBuildEntityFields(EntityBuilderProduct $subject, array $result): array
    {
        return $this->updateFields($result);
    }
}
