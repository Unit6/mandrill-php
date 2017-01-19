<?php
/*
 * This file is part of the Mandrill package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Mandrill;

/**
 * Mandrill Exception Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Exception extends \Exception
{
    public static $map = array(
        'ValidationError'               => 'ValidationError',
        'Invalid_Key'                   => 'KeyInvalid',
        'PaymentRequired'               => 'PaymentRequired',
        'Unknown_Subaccount'            => 'SubaccountUnknown',
        'Unknown_Template'              => 'TemplateUnknown',
        'ServiceUnavailable'            => 'ServiceUnavailable',
        'Unknown_Message'               => 'MessageUnknown',
        'Invalid_Tag_Name'              => 'TagNameInvalid',
        'Invalid_Reject'                => 'RejectInvalid',
        'Unknown_Sender'                => 'SenderUnknown',
        'Unknown_Url'                   => 'UrlUnknown',
        'Unknown_TrackingDomain'        => 'TrackingDomainUnknown',
        'Invalid_Template'              => 'TemplateInvalid',
        'Unknown_Webhook'               => 'WebhookUnknown',
        'Unknown_InboundDomain'         => 'InboundDomainUnknown',
        'Unknown_InboundRoute'          => 'InboundRouteUnknown',
        'Unknown_Export'                => 'ExportUnknown',
        'IP_ProvisionLimit'             => 'IPProvisionLimit',
        'Unknown_Pool'                  => 'PoolUnknown',
        'NoSendingHistory'              => 'NoSendingHistory',
        'PoorReputation'                => 'PoorReputation',
        'Unknown_IP'                    => 'IPUnknown',
        'Invalid_EmptyDefaultPool'      => 'EmptyDefaultPoolInvalid',
        'Invalid_DeleteDefaultPool'     => 'DeleteDefaultPoolInvalid',
        'Invalid_DeleteNonEmptyPool'    => 'DeleteNonEmptyPoolInvalid',
        'Invalid_CustomDNS'             => 'CustomDNSInvalid',
        'Invalid_CustomDNSPending'      => 'CustomDNSPendingInvalid',
        'Metadata_FieldLimit'           => 'MetadataFieldLimit',
        'Unknown_MetadataField'         => 'MetadataFieldUnknown'
    );

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}