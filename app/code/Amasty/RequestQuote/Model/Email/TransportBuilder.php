<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Model\Email;

use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\Template\FactoryInterface;
use Magento\Framework\Mail\Template\SenderResolverInterface;
use Magento\Framework\Mail\TransportInterfaceFactory;
use Magento\Framework\ObjectManagerInterface;
use Amasty\RequestQuote\Model\Email\MessageBuilderFactory;

class TransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
{
    /**
     * @var array
     */
    private $parts = [];

    /**
     * @var MessageBuilderFactory
     */
    private $messageBuilderFactory;

    public function __construct(
        FactoryInterface $templateFactory,
        MessageInterface $message,
        SenderResolverInterface $senderResolver,
        ObjectManagerInterface $objectManager,
        TransportInterfaceFactory $mailTransportFactory,
        MessageBuilderFactory $messageBuilderFactory
    ) {
        $this->messageBuilderFactory = $messageBuilderFactory;

        parent::__construct(
            $templateFactory,
            $message,
            $senderResolver,
            $objectManager,
            $mailTransportFactory
        );
    }

    /**
     * @param string $body
     * @param string $quoteIncrementId
     * @param string $mimeType
     * @param string $disposition
     * @param string $encoding
     * @return $this
     */
    public function addAttachment(
        $body,
        $quoteIncrementId,
        $mimeType = \Zend_Mime::TYPE_OCTETSTREAM,
        $disposition = \Zend_Mime::DISPOSITION_ATTACHMENT,
        $encoding = \Zend_Mime::ENCODING_BASE64
    ) {
        if (method_exists($this->message, 'createAttachment')) {
            $this->message->createAttachment(
                $body,
                $mimeType,
                $disposition,
                $encoding,
                sprintf('quote_%s.pdf', $quoteIncrementId)
            );
        } else {
            $attachment = new \Zend\Mime\Part($body);
            $attachment->encoding = $encoding;
            $attachment->type = $mimeType;
            $attachment->disposition = $disposition;
            $attachment->filename = sprintf('quote_%s.pdf', $quoteIncrementId);
            $this->parts[] = $attachment;
        }

        return $this;
    }

    /**
     * @return $this|TransportBuilder
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function prepareMessage()
    {
        parent::prepareMessage();

        /**
         * @var MessageBuilder $messageBuilder
         */
        $messageBuilder = $this->messageBuilderFactory->create();
        $this->message = $messageBuilder
            ->setOldMessage($this->message)
            ->setMessageParts($this->parts)
            ->build();

        return $this;
    }
}
