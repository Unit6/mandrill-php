<?php
/*
 * This file is part of the DocuSign package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\DocuSign\Resource;

use Unit6\DocuSign;
use Unit6\DocuSign\Model;
use Unit6\DocuSign\Service;

/**
 * DocuSign Envelope Resource Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Envelope extends DocuSign\Resource
{
    protected $idField = 'envelopeId';

    public function __construct(array $response = array())
    {
        parent::__construct($response);
    }

    public function getDocuments()
    {
        return $this->get($this->getDocumentsUri());
    }

    public function getRecipients()
    {
        $recipients = $this->get($this->getRecipientsUri());

        foreach ($recipients as $type => &$rows) {
            if (empty($rows) || ! is_array($rows)) {
                continue;
            }

            foreach ($rows as $i => &$row) {
                $recipient = new Recipient($row);

                $recipient->setType($type);
                $recipient->setEnvelopeId($this->id);

                $row = $recipient;
            }
        }

        return $recipients;
    }

    public function getCustomFields()
    {
        return $this->get($this->getCustomFieldsUri());
    }

    public function getNotification()
    {
        return $this->get($this->getNotificationUri());
    }

    public function getDocumentsCombined()
    {
        return $this->get($this->getDocumentsCombinedUri());
    }

    public function getCertificate()
    {
        return $this->get($this->getCertificateUri());
    }

    public function getTemplates()
    {
        return $this->get($this->getTemplatesUri());
    }
}