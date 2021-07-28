<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Import\Source\Type\Range;

use Amasty\QuickOrder\Api\Source\SourceReaderInterface;

class Reader implements SourceReaderInterface
{
    const TYPE_ID = 'range';

    /**
     * @var array
     */
    private $sourceArray;

    public function __construct(array $sourceArray)
    {
        $this->sourceArray = $sourceArray;
        reset($this->sourceArray);
    }

    /**
     * @return array|bool
     */
    public function readRow()
    {
        $currentRow = current($this->sourceArray);
        next($this->sourceArray);

        return $currentRow;
    }

    /**
     * @return int
     */
    public function estimateRecordsCount(): int
    {
        return count($this->sourceArray);
    }
}
