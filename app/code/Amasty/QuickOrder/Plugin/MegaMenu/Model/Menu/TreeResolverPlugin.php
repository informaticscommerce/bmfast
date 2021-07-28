<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Plugin\MegaMenu\Model\Menu;

use Amasty\MegaMenu\Model\Menu\TreeResolver;
use Amasty\QuickOrder\Plugin\AbstractMenuPlugin;

class TreeResolverPlugin extends AbstractMenuPlugin
{
    /**
     * @phpstan-ignore-next-line
     *
     * @param TreeResolver $treeResolver
     * @param array $additionalLinks
     * @return array
     */
    public function afterGetAdditionalLinks(TreeResolver $treeResolver, array $additionalLinks)
    {
        if ($this->isShowLink()) {
            $additionalLinks = $this->populateAdditionalLinks($additionalLinks);
        }

        return $additionalLinks;
    }

    /**
     * @param array $additionalLinks
     * @return array
     */
    private function populateAdditionalLinks(array $additionalLinks)
    {
        return array_merge($additionalLinks, [$this->getNodeAsArray()]);
    }
}
