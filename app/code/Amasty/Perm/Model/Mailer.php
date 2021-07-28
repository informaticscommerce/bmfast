<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */


namespace Amasty\Perm\Model;

use Amasty\Perm\Helper\Data as PermHelper;
use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\TransportBuilder;

class Mailer
{
    const SCOPE_MESSAGES_ENABLED = 'amasty_perm/messages/enabled';
    const SCOPE_MESSAGES_IDENTIFY = 'amasty_perm/messages/identity';
    const SCOPE_MESSAGES_TEMPLATE = 'amasty_perm/messages/template';
    const SCOPE_MESSAGES_ADMIN_NAME = 'amasty_perm/messages/admin_name';
    const SCOPE_MESSAGES_ADMIN_EMAIL = 'amasty_perm/messages/admin_email';
    const SCOPE_MESSAGES_SEE_OTHER_DEALERS = 'amasty_perm/messages/see_other_dealers';

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var PermHelper
     */
    private $permHelper;

    public function __construct(
        TransportBuilder $transportBuilder,
        PermHelper $permHelper
    ) {
        $this->transportBuilder = $transportBuilder;
        $this->permHelper = $permHelper;
    }

    public function send($storeId, array $emailsList, array $vars)
    {
        if ($this->permHelper->getScopeValue(self::SCOPE_MESSAGES_ENABLED) === '1' && count($emailsList) > 0) {
            $this->transportBuilder
                ->setTemplateIdentifier($this->permHelper->getScopeValue(self::SCOPE_MESSAGES_TEMPLATE))
                ->setTemplateOptions(
                    [
                        'area'  => Area::AREA_FRONTEND,
                        'store' => $storeId
                    ]
                )->setTemplateVars($vars)
                ->setFrom($this->permHelper->getScopeValue(self::SCOPE_MESSAGES_IDENTIFY));

            foreach ($emailsList as $emailData) {
                $this->transportBuilder->addTo($emailData['email'], $emailData['name']);
            }

            $transport = $this->transportBuilder->getTransport();
            $transport->sendMessage();
        }
    }
}
