<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Export\Provider;

use Amasty\QuickOrder\Api\Export\ResourceInterface;
use Amasty\QuickOrder\Api\Export\ProviderInterface;

class OptionProvider implements ProviderInterface
{
    /**
     * @var ResourceInterface|null
     */
    private $optionResource;

    /**
     * @var ResourceInterface|null
     */
    private $valueResource;

    /**
     * @var array
     */
    private $optionCache;

    /**
     * @var array
     */
    private $valueCache;

    public function __construct(?ResourceInterface $optionResource = null, ?ResourceInterface $valueResource = null)
    {
        $this->optionResource = $optionResource;
        $this->valueResource = $valueResource;
    }

    /**
     * @param array $skuArray
     */
    public function initData(array $skuArray): void
    {
        if ($this->optionResource) {
            $this->optionCache = $this->optionResource->execute($skuArray);
        }
        if ($this->valueResource) {
            $this->valueCache = $this->valueResource->execute($skuArray);
        }
    }

    /**
     * @return array
     * @throws \RuntimeException
     */
    public function getValueCache(): array
    {
        if ($this->valueCache === null) {
            $this->throwDataException();
        }
        return $this->valueCache;
    }

    /**
     * @return array
     * @throws \RuntimeException
     */
    public function getOptionCache(): array
    {
        if ($this->optionCache === null) {
            $this->throwDataException();
        }
        return $this->optionCache;
    }

    /**
     * @throws \RuntimeException
     */
    private function throwDataException()
    {
        throw new \RuntimeException('Need initialize cache for: ' . static::class);
    }

    public function getOption(int $optionId): ?string
    {
        return $this->getOptionCache()[$optionId] ?? null;
    }

    public function getValue(string $optionId): ?string
    {
        return $this->getValueCache()[$this->convertOptionId($optionId)] ?? null;
    }

    protected function convertOptionId(string $optionId): int
    {
        return (int) $optionId;
    }
}
