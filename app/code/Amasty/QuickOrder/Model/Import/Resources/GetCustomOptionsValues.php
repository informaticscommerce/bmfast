<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Import\Resources;

use Amasty\QuickOrder\Api\Import\ResourceInterface;
use Amasty\QuickOrder\Model\ImportExport\Resources\GetCustomOptionsValues as LoadCustomOptionsValues;

class GetCustomOptionsValues implements ResourceInterface
{
    /**
     * @var LoadCustomOptionsValues
     */
    private $loadCustomOptionValues;

    public function __construct(LoadCustomOptionsValues $loadCustomOptionValues)
    {
        $this->loadCustomOptionValues = $loadCustomOptionValues;
    }

    public function execute(array $skuArray = []): array
    {
        $loadedData = $this->loadCustomOptionValues->execute($skuArray, [
            'title' => sprintf('LOWER(%s)', $this->loadCustomOptionValues->getConnection()->getIfNullSql(
                'cpott_current.title',
                'cpott_default.title'
            )),
            'value_id' => 'cpotv.option_type_id',
            'sku' => 'cpe.sku'
        ]);

        $data = [];
        foreach ($loadedData as $row) {
            $data[$row['title']][$row['sku']] = $row['value_id'];
        }

        return $data;
    }
}
