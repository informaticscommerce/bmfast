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

class ToolbarConfigProcessor implements LayoutProcessorInterface
{
    const CLEAR_URL = 'amasty_quickorder/category/unselectAll';

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
        if (isset($jsLayout['components']['selected_toolbar']['config'])) {
            $jsLayout['components']['selected_toolbar']['config']['clearUrl'] = $this->getUrl(static::CLEAR_URL);
        }

        return $jsLayout;
    }

    private function getUrl(string $route): string
    {
        return $this->urlBuilder->getUrl($route);
    }
}
