<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Plugin\Infortis\UltraMegamenu\Block;

use Amasty\QuickOrder\Plugin\AbstractMenuPlugin;
use Infortis\UltraMegamenu\Block\Navigation;

class NavigationPlugin extends AbstractMenuPlugin
{
    /**
     * @phpstan-ignore-next-line
     *
     * @param Navigation $subject
     * @param string $html
     * @return string
     */
    public function afterGetMegamenuHtml(Navigation $subject, string $html)
    {
        if ($this->isShowLink()) {
            $html .= $this->getNodeHtml();
        }

        return $html;
    }
}
