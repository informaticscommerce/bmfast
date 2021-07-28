<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Import\Source\Type\Xml;

use Amasty\QuickOrder\Api\Source\SourceReaderInterface;
use Magento\Framework\Filesystem\File\ReadInterface as FileReader;

class Reader implements SourceReaderInterface
{
    const TYPE_ID = 'xml';

    const ITEM_XPATH = 'item_path';

    const DEFAULT_SETTINGS = [
        self::ITEM_XPATH => null
    ];

    /**
     * @var array
     */
    private $config;

    /**
     * @var FileReader
     */
    private $fileReader;

    /**
     * @var \SimpleXMLElement
     */
    private $document;

    /**
     * @var \Generator
     */
    private $generator;

    /**
     * @var \SimpleXMLElement[]|\SimpleXMLElement
     */
    private $entityNodes;

    public function __construct(
        FileReader $fileReader,
        array $config
    ) {
        $this->fileReader = $fileReader;
        $this->config = array_merge(self::DEFAULT_SETTINGS, $config);
    }

    /**
     * @return array|bool
     */
    public function readRow()
    {
        if ($this->document === null) {
            $this->initDocument();
        }

        $row = $this->generator->current();
        $this->generator->next();

        return is_array($row) ? $row : false;
    }

    protected function initDocument()
    {
        $contents = $this->fileReader->readAll();
        $this->document = new \SimpleXMLElement($contents);
        if ($xpath = $this->config[self::ITEM_XPATH]) {
            $this->entityNodes = $this->document->xpath($xpath);
        } else {
            $this->entityNodes = $this->document;
        }
        $this->generator = $this->fetchRecord();
    }

    /**
     * @return \Generator
     */
    protected function fetchRecord(): \Generator
    {
        foreach ($this->entityNodes as $entityNode) {
            yield (array)$entityNode;
        }
    }

    public function estimateRecordsCount(): int
    {
        if ($this->document === null) {
            $this->initDocument();
        }

        return count($this->entityNodes);
    }
}
