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
 * Mandrill URLs Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Urls extends Mandrill\Service
{
    /**
     * Constructs the internal representation of the Mandrill URLs service.
     */
    public function __construct(Mandrill\Client $client)
    {
        parent::__construct($client);
    }

    /**
     * Get the 100 most clicked URLs
     *
     * @return array the 100 most clicked URLs and their stats
     *     - return[] struct the individual URL stats
     *         - url string the URL to be tracked
     *         - sent integer the number of emails that contained the URL
     *         - clicks integer the number of times the URL has been clicked from a tracked email
     *         - unique_clicks integer the number of unique emails that have generated clicks for this URL
     */
    public function getList()
    {
        return $this->client->request('urls/list');
    }

    /**
     * Return the 100 most clicked URLs that match the search query given
     *
     * @param string $q a search query
     *
     * @return array the 100 most clicked URLs matching the search query
     *     - return[] struct the URL matching the query
     *         - url string the URL to be tracked
     *         - sent integer the number of emails that contained the URL
     *         - clicks integer the number of times the URL has been clicked from a tracked email
     *         - unique_clicks integer the number of unique emails that have generated clicks for this URL
     */
    public function search($q)
    {
        $params = array(
            'q' => $q
        );

        return $this->client->request('urls/search', $params);
    }

    /**
     * Return the recent history (hourly stats for the last 30 days) for a url
     *
     * @param string $url an existing URL
     *
     * @return array the array of history information
     *     - return[] struct the information for a single hour
     *         - time string the hour as a UTC date string in YYYY-MM-DD HH:MM:SS format
     *         - sent integer the number of emails that were sent with the URL during the hour
     *         - clicks integer the number of times the URL was clicked during the hour
     *         - unique_clicks integer the number of unique clicks generated for emails sent with this URL during the hour
     */
    public function timeSeries($url)
    {
        $params = array(
            'url' => $url
        );

        return $this->client->request('urls/time-series', $params);
    }

    /**
     * Get the list of tracking domains set up for this account
     *
     * @return array the tracking domains and their status
     *     - return[] struct the individual tracking domain
     *         - domain string the tracking domain name
     *         - created_at string the date and time that the tracking domain was added as a UTC string in YYYY-MM-DD HH:MM:SS format
     *         - last_tested_at string when the domain's DNS settings were last tested as a UTC string in YYYY-MM-DD HH:MM:SS format
     *         - cname struct details about the domain's CNAME record
     *             - valid boolean whether the domain's CNAME record is valid for use with Mandrill
     *             - valid_after string when the domain's CNAME record will be considered valid for use with Mandrill as a UTC string in YYYY-MM-DD HH:MM:SS format. If set, this indicates that the record is valid now, but was previously invalid, and Mandrill will wait until the record's TTL elapses to start using it.
     *             - error string an error describing the CNAME record, or null if the record is correct
     *         - valid_tracking boolean whether this domain can be used as a tracking domain for email.
     */
    public function trackingDomains()
    {
        return $this->client->request('urls/tracking-domains');
    }

    /**
     * Add a tracking domain to your account
     *
     * @param string $domain a domain name
     *
     * @return struct information about the domain
     *     - domain string the tracking domain name
     *     - created_at string the date and time that the tracking domain was added as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - last_tested_at string when the domain's DNS settings were last tested as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - cname struct details about the domain's CNAME record
     *         - valid boolean whether the domain's CNAME record is valid for use with Mandrill
     *         - valid_after string when the domain's CNAME record will be considered valid for use with Mandrill as a UTC string in YYYY-MM-DD HH:MM:SS format. If set, this indicates that the record is valid now, but was previously invalid, and Mandrill will wait until the record's TTL elapses to start using it.
     *         - error string an error describing the CNAME record, or null if the record is correct
     *     - valid_tracking boolean whether this domain can be used as a tracking domain for email.
     */
    public function addTrackingDomain($domain)
    {
        $params = array(
            'domain' => $domain
        );

        return $this->client->request('urls/add-tracking-domain', $params);
    }

    /**
     * Checks the CNAME settings for a tracking domain. The domain
     * must have been added already with the add-tracking-domain call
     *
     * @param string $domain an existing tracking domain name
     *
     * @return struct information about the tracking domain
     *     - domain string the tracking domain name
     *     - created_at string the date and time that the tracking domain was added as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - last_tested_at string when the domain's DNS settings were last tested as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - cname struct details about the domain's CNAME record
     *         - valid boolean whether the domain's CNAME record is valid for use with Mandrill
     *         - valid_after string when the domain's CNAME record will be considered valid for use with Mandrill as a UTC string in YYYY-MM-DD HH:MM:SS format. If set, this indicates that the record is valid now, but was previously invalid, and Mandrill will wait until the record's TTL elapses to start using it.
     *         - error string an error describing the CNAME record, or null if the record is correct
     *     - valid_tracking boolean whether this domain can be used as a tracking domain for email.
     */
    public function checkTrackingDomain($domain)
    {
        $params = array(
            'domain' => $domain
        );

        return $this->client->request('urls/check-tracking-domain', $params);
    }
}