<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Api;

/**
 * Interface RemarkServiceInterface
 */
interface RemarkServiceInterface
{
    /**
     * @param string $remark
     *
     * @return void
     */
    public function save($remark);
}
