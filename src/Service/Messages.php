<?php
/*
 * This file is part of the Mandrill package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Mandrill\Service;

use Unit6\Mandrill;
use Unit6\Mandrill\Model;
use Unit6\Mandrill\Exception;

/**
 * Mandrill Messages Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Messages extends Mandrill\Service
{
    /**
     * Constructs the internal representation of the Mandrill Messages service.
     */
    public function __construct(Mandrill\Client $client)
    {
        parent::__construct($client);
    }

    /**
     * Send a new transactional message through Mandrill
     *
     * @param Model\Message $message The information on the message to send
     * @param boolean       $async   Enable a background sending mode that is optimized for bulk sending. In async mode, messages/send will immediately return a status of "queued" for every recipient. To handle rejections when sending in async mode, set up a webhook for the 'reject' event. Defaults to false for messages with no more than 10 recipients; messages with more than 10 recipients are always sent asynchronously, regardless of the value of async.
     * @param string        $ip_pool The name of the dedicated ip pool that should be used to send the message. If you do not have any dedicated IPs, this parameter has no effect. If you specify a pool that does not exist, your default pool will be used instead.
     * @param string        $send_at When this message should be sent as a UTC timestamp in YYYY-MM-DD HH:MM:SS format. If you specify a time in the past, the message will be sent immediately. An additional fee applies for scheduled email, and this feature is only available to accounts with a positive balance.
     *
     * @return array of structs for each recipient containing the key "email"
     *              with the email address, and details of the message status for that recipient
     *     - return[] struct the sending results for a single recipient
     *         - email string the email address of the recipient
     *         - status string the sending status of the recipient - either "sent", "queued", "scheduled", "rejected", or "invalid"
     *         - reject_reason string the reason for the rejection if the recipient status is "rejected" - one of "hard-bounce", "soft-bounce", "spam", "unsub", "custom", "invalid-sender", "invalid", "test-mode-limit", or "rule"
     *         - _id string the message's unique id
     */
    public function send(Model\Message $message, $async = false, $ip_pool = null, $send_at = null)
    {
        $uri = 'messages/send';

        $params = array(
            'message' => $message->getData(),
            'async'   => $async,
            'ip_pool' => $ip_pool,
            'send_at' => $send_at
        );

        if ($message->hasTemplate()) {
            // alter the endpoint URI to account for a template.
            $uri .= '-template';

            $template = $message->getTemplate();

            $params['template_name'] = $template->getName();
            $params['template_content'] = $template->getContent();
        } elseif ($message->hasRawMessage()) {
            // alter the endpoint URI to account for sending a raw message.
            $uri .= '-raw';

            $params['raw_message'] = $message->getRawMessage();
            $params['from_email'] = $message->getFromEmail();
            $params['from_name'] = $message->getFromName();

            $to = $message->getTo();

            if ( ! empty($to)) {
                $params['to'] = array();
                foreach ($to as $recipient) {
                    if ($recipient['type'] === Model\Recipient::TYPE_TO) {
                        $params['to'][] = $recipient['email'];
                    }
                }
            }
        }

        $params = array_merge($params, $message->getSendOptions());

        return $this->client->request($uri, $params);
    }

    /**
     * Search recently sent messages and optionally narrow by date range, tags, senders, and API keys. If no date range is specified, results within the last 7 days are returned. This method may be called up to 20 times per minute. If you need the data more often, you can use <a href="/api/docs/messages.html#method=info">/messages/info.json</a> to get the information for a single message, or <a href="http://help.mandrill.com/entries/21738186-Introduction-to-Webhooks">webhooks</a> to push activity to your own application for querying.
     *
     * @param Model\MessageQuery Search query to send.
     *
     * @return array of structs for each matching message
     *     - return[] struct the information for a single matching message
     *         - ts integer the Unix timestamp from when this message was sent
     *         - _id string the message's unique id
     *         - sender string the email address of the sender
     *         - template string the unique name of the template used, if any
     *         - subject string the message's subject line
     *         - email string the recipient email address
     *         - tags array list of tags on this message
     *             - tags[] string individual tag on this message
     *         - opens integer how many times has this message been opened
     *         - opens_detail array list of individual opens for the message
     *             - opens_detail[] struct information on an individual open
     *                 - ts integer the unix timestamp from when the message was opened
     *                 - ip string the IP address that generated the open
     *                 - location string the approximate region and country that the opening IP is located
     *                 - ua string the email client or browser data of the open
     *         - clicks integer how many times has a link been clicked in this message
     *         - clicks_detail array list of individual clicks for the message
     *             - clicks_detail[] struct information on an individual click
     *                 - ts integer the unix timestamp from when the message was clicked
     *                 - url string the URL that was clicked on
     *                 - ip string the IP address that generated the click
     *                 - location string the approximate region and country that the clicking IP is located
     *                 - ua string the email client or browser data of the click
     *         - state string sending status of this message: sent, bounced, rejected
     *         - metadata struct any custom metadata provided when the message was sent
     *     - smtp_events array a log of up to 3 smtp events for the message
     *         - smtp_events[] struct information about a specific smtp event
     *             - ts integer the Unix timestamp when the event occured
     *             - type string the message's state as a result of this event
     *             - diag string the SMTP response from the recipient's server
     */
    public function search(Model\MessageQuery $query)
    {
        $uri = 'messages/search';

        if ($query->isTimeSeries()) {
            // alter the endpoint URI to account for time series request
            // response will contain aggregated hourly stats.
            $uri .= '-time-series';
        }

        $params = $query->getData();

        return $this->client->request($uri, $params);
    }

    /**
     * Get the information for a single recently sent message
     *
     * @param string $id the unique id of the message to get - passed as the "_id" field in webhooks, send calls, or search calls
     *
     * @return struct the information for the message
     *     - ts integer the Unix timestamp from when this message was sent
     *     - _id string the message's unique id
     *     - sender string the email address of the sender
     *     - template string the unique name of the template used, if any
     *     - subject string the message's subject line
     *     - email string the recipient email address
     *     - tags array list of tags on this message
     *         - tags[] string individual tag on this message
     *     - opens integer how many times has this message been opened
     *     - opens_detail array list of individual opens for the message
     *         - opens_detail[] struct information on an individual open
     *             - ts integer the unix timestamp from when the message was opened
     *             - ip string the IP address that generated the open
     *             - location string the approximate region and country that the opening IP is located
     *             - ua string the email client or browser data of the open
     *     - clicks integer how many times has a link been clicked in this message
     *     - clicks_detail array list of individual clicks for the message
     *         - clicks_detail[] struct information on an individual click
     *             - ts integer the unix timestamp from when the message was clicked
     *             - url string the URL that was clicked on
     *             - ip string the IP address that generated the click
     *             - location string the approximate region and country that the clicking IP is located
     *             - ua string the email client or browser data of the click
     *     - state string sending status of this message: sent, bounced, rejected
     *     - metadata struct any custom metadata provided when the message was sent
     *     - smtp_events array a log of up to 3 smtp events for the message
     *         - smtp_events[] struct information about a specific smtp event
     *             - ts integer the Unix timestamp when the event occured
     *             - type string the message's state as a result of this event
     *             - diag string the SMTP response from the recipient's server
     */
    public function info($id)
    {
        $params = array(
            'id' => $id
        );

        return $this->client->request('messages/info', $params);
    }

    /**
     * Get the full content of a recently sent message
     *
     * @param string $id the unique id of the message to get - passed as the "_id" field in webhooks, send calls, or search calls
     *
     * @return struct the content of the message
     *     - ts integer the Unix timestamp from when this message was sent
     *     - _id string the message's unique id
     *     - from_email string the email address of the sender
     *     - from_name string the alias of the sender (if any)
     *     - subject string the message's subject line
     *     - to struct the message recipient's information
     *         - email string the email address of the recipient
     *         - name string the alias of the recipient (if any)
     *     - tags array list of tags on this message
     *         - tags[] string individual tag on this message
     *     - headers struct the key-value pairs of the custom MIME headers for the message's main document
     *     - text string the text part of the message, if any
     *     - html string the HTML part of the message, if any
     *     - attachments array an array of any attachments that can be found in the message
     *         - attachments[] struct information about an individual attachment
     *             - name string the file name of the attachment
     *             - type string the MIME type of the attachment
     *             - content string the content of the attachment as a base64 encoded string
     */
    public function content($id)
    {
        $params = array(
            'id' => $id
        );

        return $this->client->request('messages/content', $params);
    }

    /**
     * Parse the full MIME document for an email message, returning the content of the message broken into its constituent pieces
     *
     * @param string $raw_message the full MIME document of an email message
     *
     * @return struct the parsed message
     *     - subject string the subject of the message
     *     - from_email string the email address of the sender
     *     - from_name string the alias of the sender (if any)
     *     - to array an array of any recipients in the message
     *         - to[] struct the information on a single recipient
     *             - email string the email address of the recipient
     *             - name string the alias of the recipient (if any)
     *     - headers struct the key-value pairs of the MIME headers for the message's main document
     *     - text string the text part of the message, if any
     *     - html string the HTML part of the message, if any
     *     - attachments array an array of any attachments that can be found in the message
     *         - attachments[] struct information about an individual attachment
     *             - name string the file name of the attachment
     *             - type string the MIME type of the attachment
     *             - binary boolean if this is set to true, the attachment is not pure-text, and the content will be base64 encoded
     *             - content string the content of the attachment as a text string or a base64 encoded string based on the attachment type
     *     - images array an array of any embedded images that can be found in the message
     *         - images[] struct information about an individual image
     *             - name string the Content-ID of the embedded image
     *             - type string the MIME type of the image
     *             - content string the content of the image as a base64 encoded string
     */
    public function parse($raw_message)
    {
        $params = array(
            'raw_message' => $raw_message
        );

        return $this->client->request('messages/parse', $params);
    }

    /**
     * Queries your scheduled emails by sender or recipient, or both.
     *
     * @param string $to an optional recipient address to restrict results to
     *
     * @return array a list of up to 1000 scheduled emails
     *     - return[] struct a scheduled email
     *         - _id string the scheduled message id
     *         - created_at string the UTC timestamp when the message was created, in YYYY-MM-DD HH:MM:SS format
     *         - send_at string the UTC timestamp when the message will be sent, in YYYY-MM-DD HH:MM:SS format
     *         - from_email string the email's sender address
     *         - to string the email's recipient
     *         - subject string the email's subject
     */
    public function listScheduled($to = null)
    {
        $params = array(
            'to' => $to
        );

        return $this->client->request('messages/list-scheduled', $params);
    }

    /**
     * Cancels a scheduled email.
     *
     * @param string $id a scheduled email id, as returned by any of the messages/send calls or messages/list-scheduled
     *
     * @return struct information about the scheduled email that was cancelled.
     *     - _id string the scheduled message id
     *     - created_at string the UTC timestamp when the message was created, in YYYY-MM-DD HH:MM:SS format
     *     - send_at string the UTC timestamp when the message will be sent, in YYYY-MM-DD HH:MM:SS format
     *     - from_email string the email's sender address
     *     - to string the email's recipient
     *     - subject string the email's subject
     */
    public function cancelScheduled($id)
    {
        $params = array(
            'id' => $id
        );

        return $this->client->request('messages/cancel-scheduled', $params);
    }

    /**
     * Reschedules a scheduled email.
     *
     * @param string $id a scheduled email id, as returned by any of the messages/send calls or messages/list-scheduled
     * @param string $send_at the new UTC timestamp when the message should sent. Mandrill can't time travel, so if you specify a time in past the message will be sent immediately
     *
     * @return struct information about the scheduled email that was rescheduled.
     *     - _id string the scheduled message id
     *     - created_at string the UTC timestamp when the message was created, in YYYY-MM-DD HH:MM:SS format
     *     - send_at string the UTC timestamp when the message will be sent, in YYYY-MM-DD HH:MM:SS format
     *     - from_email string the email's sender address
     *     - to string the email's recipient
     *     - subject string the email's subject
     */
    public function reschedule($id, $send_at)
    {
        $params = array(
            'id' => $id,
            'send_at' => $send_at
        );

        return $this->client->request('messages/reschedule', $params);
    }
}