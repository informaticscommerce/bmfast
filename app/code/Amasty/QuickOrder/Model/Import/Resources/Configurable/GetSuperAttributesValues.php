<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Import\Resources\Configurable;

use Amasty\QuickOrder\Api\Import\ResourceInterface;
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
            sprintf('LOWER(%s)', $this->loadSuperAttributesValues->getConnection()->getIfNullSql(
                'eaol_current.value',
                'eaol_default.value'
            )),
            'eao.option_id'
        ]);
    }
}
