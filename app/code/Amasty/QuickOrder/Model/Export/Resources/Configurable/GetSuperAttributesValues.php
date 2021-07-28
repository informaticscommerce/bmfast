<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Export\Resources\Configurable;

use Amasty\QuickOrder\Api\Export\ResourceInterface;
use Amasty\QuickOrder\Model\ImportExport\Resources\Configurable\GetSuperAttributesValues as LoadSuperAttributesValues;

class GetSuperAttributesValues implements ResourceInterface
{
    /**
     * @var LoadSuperAttributesValues
     */
    private $loadSuperAttributesValues;

    public function __construct(LoadSuperAttributesValues $loadSuperAttributesValues)
    {
        $this->loadSuperAttributesValues = $loadSuperAttributesValues;
    }

    public function execute(array $skuArray = []): array
    {
        return $this->loadSuperAttributesValues->execute($skuArray, [
            'eao.option_id',
            $this->loadSuperAttributesValues->getConnection()->getIfNullSql(
                'eaol_current.value',
                'eaol_default.value'
            )
        ]);
    }
}
