<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Block\Grid;

use Amasty\QuickOrder\Model\ConfigProvider;

class PagerConfigProcessor implements LayoutProcessorInterface
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    public function __construct(ConfigProvider $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    public function process($jsLayout): array
    {
        if (isset($jsLayout['components']['pager']['config'])) {
            $jsLayout['components']['pager']['config']['pageSize'] = $this->configProvider->getPageSize();
        }

        return $jsLayout;
    }
}
