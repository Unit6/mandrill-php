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
 * Mandrill Inbound Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Inbound extends Mandrill\Service
{
    /**
     * Constructs the internal representation of the Mandrill Inbound service.
     */
    public function __construct(Mandrill\Client $client)
    {
        parent::__construct($client);
    }

    /**
     * List the domains that have been configured for inbound delivery
     *
     * @return array the inbound domains associated with the account
     *     - return[] struct the individual domain info
     *         - domain string the domain name that is accepting mail
     *         - created_at string the date and time that the inbound domain was added as a UTC string in YYYY-MM-DD HH:MM:SS format
     *         - valid_mx boolean true if this inbound domain has successfully set up an MX record to deliver mail to the Mandrill servers
     */
    public function domains()
    {
        return $this->client->request('inbound/domains');
    }

    /**
     * Add an inbound domain to your account
     *
     * @param string $domain a domain name
     *
     * @return struct information about the domain
     *     - domain string the domain name that is accepting mail
     *     - created_at string the date and time that the inbound domain was added as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - valid_mx boolean true if this inbound domain has successfully set up an MX record to deliver mail to the Mandrill servers
     */
    public function addDomain($domain)
    {
        $params = array(
            'domain' => $domain
        );

        return $this->client->request('inbound/add-domain', $params);
    }

    /**
     * Check the MX settings for an inbound domain. The domain must have
     * already been added with the add-domain call
     *
     * @param string $domain an existing inbound domain
     *
     * @return struct information about the inbound domain
     *     - domain string the domain name that is accepting mail
     *     - created_at string the date and time that the inbound domain was added as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - valid_mx boolean true if this inbound domain has successfully set up an MX record to deliver mail to the Mandrill servers
     */
    public function checkDomain($domain)
    {
        $params = array(
            'domain' => $domain
        );

        return $this->client->request('inbound/check-domain', $params);
    }

    /**
     * Delete an inbound domain from the account. All mail will stop
     * routing for this domain immediately.
     *
     * @param string $domain an existing inbound domain
     *
     * @return struct information about the deleted domain
     *     - domain string the domain name that is accepting mail
     *     - created_at string the date and time that the inbound domain was added as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - valid_mx boolean true if this inbound domain has successfully set up an MX record to deliver mail to the Mandrill servers
     */
    public function deleteDomain($domain)
    {
        $params = array(
            'domain' => $domain
        );

        return $this->client->request('inbound/delete-domain', $params);
    }

    /**
     * List the mailbox routes defined for an inbound domain
     *
     * @param string $domain the domain to check
     *
     * @return array the routes associated with the domain
     *     - return[] struct the individual mailbox route
     *         - id string the unique identifier of the route
     *         - pattern string the search pattern that the mailbox name should match
     *         - url string the webhook URL where inbound messages will be published
     */
    public function routes($domain)
    {
        $params = array(
            'domain' => $domain
        );

        return $this->client->request('inbound/routes', $params);
    }

    /**
     * Add a new mailbox route to an inbound domain
     *
     * @param string $domain an existing inbound domain
     * @param string $pattern the search pattern that the mailbox name should match
     * @param string $url the webhook URL where the inbound messages will be published
     *
     * @return struct the added mailbox route information
     *     - id string the unique identifier of the route
     *     - pattern string the search pattern that the mailbox name should match
     *     - url string the webhook URL where inbound messages will be published
     */
    public function addRoute($domain, $pattern, $url)
    {
        $params = array(
            'domain' => $domain,
            'pattern' => $pattern,
            'url' => $url
        );

        return $this->client->request('inbound/add-route', $params);
    }

    /**
     * Update the pattern or webhook of an existing inbound mailbox route.
     * If null is provided for any fields, the values will remain unchanged.
     *
     * @param string $id the unique identifier of an existing mailbox route
     * @param string $pattern the search pattern that the mailbox name should match
     * @param string $url the webhook URL where the inbound messages will be published
     *
     * @return struct the updated mailbox route information
     *     - id string the unique identifier of the route
     *     - pattern string the search pattern that the mailbox name should match
     *     - url string the webhook URL where inbound messages will be published
     */
    public function updateRoute($id, $pattern = null, $url = null)
    {
        $params = array(
            'id' => $id,
            'pattern' => $pattern,
            'url' => $url
        );

        return $this->client->request('inbound/update-route', $params);
    }

    /**
     * Delete an existing inbound mailbox route
     *
     * @param string $id the unique identifier of an existing route
     *
     * @return struct the deleted mailbox route information
     *     - id string the unique identifier of the route
     *     - pattern string the search pattern that the mailbox name should match
     *     - url string the webhook URL where inbound messages will be published
     */
    public function deleteRoute($id)
    {
        $params = array(
            'id' => $id
        );

        return $this->client->request('inbound/delete-route', $params);
    }

    /**
     * Take a raw MIME document destined for a domain with inbound domains
     * set up, and send it to the inbound hook exactly as if it had been
     * sent over SMTP
     *
     * @param string $raw_message the full MIME document of an email message
     * @param array|null $to optionally define the recipients to receive the message - otherwise we'll use the To, Cc, and Bcc headers provided in the document
     *     - to[] string the email address of the recipient
     * @param string $mail_from the address specified in the MAIL FROM stage of the SMTP conversation. Required for the SPF check.
     * @param string $helo the identification provided by the client mta in the MTA state of the SMTP conversation. Required for the SPF check.
     * @param string $client_address the remote MTA's ip address. Optional; required for the SPF check.
     *
     * @return array an array of the information for each recipient in the message (usually one) that matched an inbound route
     *     - return[] struct the individual recipient information
     *         - email string the email address of the matching recipient
     *         - pattern string the mailbox route pattern that the recipient matched
     *         - url string the webhook URL that the message was posted to
     */
    public function sendRaw($raw_message, $to = null, $mail_from = null, $helo = null, $client_address = null)
    {
        $params = array(
            'raw_message' => $raw_message,
            'to' => $to,
            'mail_from' => $mail_from,
            'helo' => $helo,
            'client_address' => $client_address
        );

        return $this->client->request('inbound/send-raw', $params);
    }
}