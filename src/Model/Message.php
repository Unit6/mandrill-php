<?php
/*
 * This file is part of the Mandrill package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Mandrill\Model;

use Unit6\Mandrill;
use Unit6\Mandrill\Exception;

/**
 * Mandrill Message Model Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Message extends Mandrill\Model
{
    // the message state.
    const STATE_BOUNCED      = 'bounced';
    const STATE_REJECTED     = 'rejected';
    const STATE_SENT         = 'sent';
    const STATE_SOFT_BOUNCED = 'soft-bounced';
    const STATE_SPAM         = 'spam';
    const STATE_UNSUBSCRIBED = 'unsub';

    protected $sendOptions = array();
    protected $template;

    public function __construct(array $row = array())
    {
        $this->assignData($row);
    }

    public function setSendOptions(array $options = array())
    {
        $this->sendOptions = $options;
    }

    public function getSendOptions()
    {
        return $this->sendOptions;
    }

    public function setTemplate(Template $template)
    {
        $this->template = $template;

        #unset($this->data['html']);
        #unset($this->data['text']);
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function hasTemplate()
    {
        return is_a($this->template, __NAMESPACE__ . '\\Template');
    }

    public function hasRawMessage()
    {
        $rawMessage = $this->getRawMessage();

        return ( ! is_null($rawMessage) && strlen($rawMessage) > 0);
    }

    public function addTag($tag)
    {
        $this->appendToList('tags', $tag);
    }

    public function addGoogleAnalyticsDomain($domain)
    {
        $this->appendToList('google_analytics_domains', $domain);
    }

    public function addHeader($name, $value)
    {
        $this->appendToList('google_analytics_domains', $name, $value);
    }

    public function addMetadata($name, $value)
    {
        $this->appendToList('metadata', $name, $value);
    }

    public function addGlobalMergeVar($name, $content)
    {
        $item = array(
            'name' => $name,
            'content' => $content
        );

        $this->appendToList('global_merge_vars', $item);
    }

    public function addRecipient(Recipient $recipient)
    {
        $this->appendToList('to', $recipient->getData());

        $this->addRecipientMetadata($recipient);

        $this->addRecipientMergeVars($recipient);
    }

    public function addRecipientMetadata(Recipient $recipient)
    {
        $metadata = $recipient->getMetadata();

        if ( ! empty($metadata)) {
            $item = array(
                'rcpt' => $recipient->getEmail(),
                'values' => $metadata
            );

            $this->appendToList('recipient_metadata', $item);
        }
    }

    public function addRecipientMergeVars(Recipient $recipient)
    {
        $merge_vars = $recipient->getMergeVars();

        if ( ! empty($merge_vars)) {
            $vars = array();

            foreach ( $merge_vars as $name => $content )
            {
                $vars[] = array(
                    'name'    => $name,
                    'content' => $content
                );
            }

            $item = array(
                'rcpt' => $recipient->getEmail(),
                'vars' => $vars
            );

            $this->appendToList('merge_vars', $item);
        }
    }

    public function addAttachment(Media $media)
    {
        if ($media->getFileSize()) {
            $this->appendToList('attachments', $media->getData());
        }
    }

    public function addImage(Media $media)
    {
        if ($media->getFileSize() && $media->isImage()) {
            $this->appendToList('images', $media->getData());
        }
    }
}