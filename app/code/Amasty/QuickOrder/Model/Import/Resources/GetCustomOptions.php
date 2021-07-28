<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Import\Resources;

use Amasty\QuickOrder\Api\Import\ResourceInterface;
use Amasty\QuickOrder\Model\ImportExport\Resources\GetCustomOptions as LoadCustomOptions;

class GetCustomOptions implements ResourceInterface
{
    /**
     * @var LoadCustomOptions
     */
    private $loadCustomOptions;

    public function __construct(LoadCustomOptions $loadCustomOptions)
    {
        $this->loadCustomOptions = $loadCustomOptions;
    }

    public function execute(array $skuArray = []): array
    {
        $loadedData = $this->loadCustomOptions->execute($skuArray, [
            'title' => sprintf('LOWER(%s)', $this->loadCustomOptions->getConnection()->getIfNullSql(
                'cpot_current.title',
                'cpot_default.title'
            )),
            'option_id' => 'cpo.option_id',
            'sku' => 'cpe.sku'
        ]);

        $data = [];
        foreach ($loadedData as $row) {
            $data[$row['title']][$row['sku']] = $row['option_id'];
        }

        return $data;
    }
}
