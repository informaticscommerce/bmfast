<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Model\Email;

use Magento\Email\Model\Template as EmailTemplate;

/**
 * Class Template
 * Need setting is_legacy in true for support layout handle with object param
 */
class Template extends EmailTemplate
{
    /**
     * @inheritDoc
     */
    public function load($modelId, $field = null)
    {
        $template = parent::load($modelId, $field);
        $template->setData('is_legacy', true);

        return $template;
    }
}
