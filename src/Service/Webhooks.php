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
 * Mandrill Webhooks Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Webhooks extends Mandrill\Service
{
    /**
     * Constructs the internal representation of the Mandrill Webhooks service.
     */
    public function __construct(Mandrill\Client $client)
    {
        parent::__construct($client);
    }

    /**
     * Get the list of all webhooks defined on the account
     *
     * @return array the webhooks associated with the account
     *     - return[] struct the individual webhook info
     *         - id integer a unique integer indentifier for the webhook
     *         - url string The URL that the event data will be posted to
     *         - description string a description of the webhook
     *         - auth_key string the key used to requests for this webhook
     *         - events array The message events that will be posted to the hook
     *             - events[] string the individual message event (send, hard_bounce, soft_bounce, open, click, spam, unsub, or reject)
     *         - created_at string the date and time that the webhook was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *         - last_sent_at string the date and time that the webhook last successfully received events as a UTC string in YYYY-MM-DD HH:MM:SS format
     *         - batches_sent integer the number of event batches that have ever been sent to this webhook
     *         - events_sent integer the total number of events that have ever been sent to this webhook
     *         - last_error string if we've ever gotten an error trying to post to this webhook, the last error that we've seen
     */
    public function getList()
    {
        return $this->client->request('webhooks/list');
    }

    /**
     * Add a new webhook
     *
     * @param Model\Webhook $webhook Instance of a webhook
     *
     * @return struct the information saved about the new webhook
     *     - id integer a unique integer indentifier for the webhook
     *     - url string The URL that the event data will be posted to
     *     - description string a description of the webhook
     *     - auth_key string the key used to requests for this webhook
     *     - events array The message events that will be posted to the hook
     *         - events[] string the individual message event (send, hard_bounce, soft_bounce, open, click, spam, unsub, or reject)
     *     - created_at string the date and time that the webhook was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - last_sent_at string the date and time that the webhook last successfully received events as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - batches_sent integer the number of event batches that have ever been sent to this webhook
     *     - events_sent integer the total number of events that have ever been sent to this webhook
     *     - last_error string if we've ever gotten an error trying to post to this webhook, the last error that we've seen
     */
    public function add(Model\Webhook $webhook)
    {
        $params = $webhook->getData();

        return $this->client->request('webhooks/add', $params);
    }

    /**
     * Given the ID of an existing webhook, return the data about it
     *
     * @param integer $id the unique identifier of a webhook belonging to this account
     *
     * @return struct the information about the webhook
     *     - id integer a unique integer indentifier for the webhook
     *     - url string The URL that the event data will be posted to
     *     - description string a description of the webhook
     *     - auth_key string the key used to requests for this webhook
     *     - events array The message events that will be posted to the hook
     *         - events[] string the individual message event (send, hard_bounce, soft_bounce, open, click, spam, unsub, or reject)
     *     - created_at string the date and time that the webhook was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - last_sent_at string the date and time that the webhook last successfully received events as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - batches_sent integer the number of event batches that have ever been sent to this webhook
     *     - events_sent integer the total number of events that have ever been sent to this webhook
     *     - last_error string if we've ever gotten an error trying to post to this webhook, the last error that we've seen
     */
    public function info($id)
    {
        $params = array(
            'id' => $id
        );

        return $this->client->request('webhooks/info', $params);
    }

    /**
     * Update an existing webhook
     *
     * @param Model\Webhook $webhook Instance of a webhook
     *
     * @return struct the information for the updated webhook
     *     - id integer a unique integer indentifier for the webhook
     *     - url string The URL that the event data will be posted to
     *     - description string a description of the webhook
     *     - auth_key string the key used to requests for this webhook
     *     - events array The message events that will be posted to the hook
     *         - events[] string the individual message event (send, hard_bounce, soft_bounce, open, click, spam, unsub, or reject)
     *     - created_at string the date and time that the webhook was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - last_sent_at string the date and time that the webhook last successfully received events as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - batches_sent integer the number of event batches that have ever been sent to this webhook
     *     - events_sent integer the total number of events that have ever been sent to this webhook
     *     - last_error string if we've ever gotten an error trying to post to this webhook, the last error that we've seen
     */
    public function update(Model\Webhook $webhook)
    {
        $params = $webhook->getData();

        return $this->client->request('webhooks/update', $params);
    }

    /**
     * Delete an existing webhook
     *
     * @param integer $id the unique identifier of a webhook belonging to this account
     *
     * @return struct the information for the deleted webhook
     *     - id integer a unique integer indentifier for the webhook
     *     - url string The URL that the event data will be posted to
     *     - description string a description of the webhook
     *     - auth_key string the key used to requests for this webhook
     *     - events array The message events that will be posted to the hook
     *         - events[] string the individual message event (send, hard_bounce, soft_bounce, open, click, spam, unsub, or reject)
     *     - created_at string the date and time that the webhook was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - last_sent_at string the date and time that the webhook last successfully received events as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - batches_sent integer the number of event batches that have ever been sent to this webhook
     *     - events_sent integer the total number of events that have ever been sent to this webhook
     *     - last_error string if we've ever gotten an error trying to post to this webhook, the last error that we've seen
     */
    public function delete($id)
    {
        $params = array(
            'id' => $id
        );

        return $this->client->request('webhooks/delete', $params);
    }
}