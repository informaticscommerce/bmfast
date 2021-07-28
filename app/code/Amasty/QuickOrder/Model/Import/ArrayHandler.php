<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Import;

use Amasty\QuickOrder\Model\Import\Source\Type\Range\Reader;
use Amasty\QuickOrder\Model\Import\Source\Type\Range\ReaderFactory;

class ArrayHandler
{
    /**
     * @var ReaderFactory
     */
    private $readerFactory;

    /**
     * @var ImportHandler
     */
    private $importHandler;

    public function __construct(
        ImportHandler $importHandler,
        ReaderFactory $readerFactory
    ) {
        $this->readerFactory = $readerFactory;
        $this->importHandler = $importHandler;
    }

    /**
     * @param array $source
     * @return array
     */
    public function import(array $source): array
    {
        /** @var Reader $reader */
        $reader = $this->readerFactory->create(['sourceArray' => $source]);

        return $this->importHandler->execute($reader);
    }
}
