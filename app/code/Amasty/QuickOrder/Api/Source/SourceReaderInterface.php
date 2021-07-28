<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


namespace Amasty\QuickOrder\Api\Source;

interface SourceReaderInterface
{
    /**
     * Returns array with row data or false if end of file reached
     * @return array|bool
     */
    public function readRow();

    /**
     * @return int
     */
    public function estimateRecordsCount(): int;
}
