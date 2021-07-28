<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Import\Resources\Configurable;

use Amasty\QuickOrder\Api\Import\ResourceInterface;
use Amasty\QuickOrder\Model\ImportExport\Resources\Configurable\GetSuperAttributes as LoadSuperAttributes;

class GetSuperAttributes implements ResourceInterface
{
    /**
     * @var LoadSuperAttributes
     */
    private $loadSuperAttributes;

    public function __construct(LoadSuperAttributes $loadSuperAttributes)
    {
        $this->loadSuperAttributes = $loadSuperAttributes;
    }

    public function execute(array $skuArray = []): array
    {
        return $this->loadSuperAttributes->execute($skuArray, [
            sprintf('LOWER(%s)', $this->loadSuperAttributes->getConnection()->getIfNullSql(
                'eal.value',
                'ea.frontend_label'
            )),
            'ea.attribute_id'
        ]);
    }
}
