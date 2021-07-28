<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Block\Grid;

use Amasty\QuickOrder\Model\MoveButton\Provider as ButtonProvider;

class MoveConfigProcessor implements LayoutProcessorInterface
{
    /**
     * @var ButtonProvider
     */
    private $buttonProvider;

    /**
     * @var array
     */
    private $requestedButtons;

    /**
     * @var string
     */
    private $mode;

    public function __construct(
        ButtonProvider $buttonProvider,
        string $mode,
        array $requestedButtons = []
    ) {
        $this->buttonProvider = $buttonProvider;
        $this->requestedButtons = $requestedButtons;
        $this->mode = $mode;
    }

    public function process($jsLayout): array
    {
        if (isset($jsLayout['components']['move_buttons']['config'])) {
            $jsLayout['components']['move_buttons']['config']['buttons'] = $this->buttonProvider->getButtons(
                $this->mode,
                $this->requestedButtons
            );
        }

        return $jsLayout;
    }
}
