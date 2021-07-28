<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Block\Category\Grid;

use Amasty\QuickOrder\Block\Grid\LayoutProcessorInterface;
use Magento\Framework\UrlInterface;

class GridConfigProcessor implements LayoutProcessorInterface
{
    const UPDATE_URL = 'amasty_quickorder/category/updateItem';
    const OPTIONS_URL = 'amasty_quickorder/category/getOptions';

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    public function __construct(UrlInterface $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    public function process($jsLayout): array
    {
        if (isset($jsLayout['components']['grid']['config'])) {
            $jsLayout['components']['grid']['config']['updateUrl'] = $this->getUrl(static::UPDATE_URL);
            $jsLayout['components']['grid']['config']['loadOptionsUrl'] = $this->getUrl(static::OPTIONS_URL);
        }

        return $jsLayout;
    }

    private function getUrl(string $route): string
    {
        return $this->urlBuilder->getUrl($route);
    }
}
