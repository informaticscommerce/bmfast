<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Export\Resources\Grouped;

use Amasty\QuickOrder\Api\Export\ResourceInterface;
use Amasty\QuickOrder\Model\ImportExport\Resources\Grouped\GetLinks as LoadLinks;

class GetLinks implements ResourceInterface
{
    /**
     * @var LoadLinks
     */
    private $loadLinks;

    public function __construct(LoadLinks $loadLinks)
    {
        $this->loadLinks = $loadLinks;
    }

    public function execute(array $skuArray = []): array
    {
        return $this->loadLinks->execute($skuArray, [
            'cpl.linked_product_id',
            $this->loadLinks->getConnection()->getIfNullSql(
                'cpev_current.value',
                'cpev_default.value'
            )
        ]);
    }
}
