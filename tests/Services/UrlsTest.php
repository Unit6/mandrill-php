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
 * Tests URLs.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class UrlsTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Urls($client);
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    /**
     * Get the 100 most clicked URLs
     */
    public function testGetUrlsList()
    {
        $response = $this->service->getList();

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('url', $row);
        $this->assertArrayHasKey('sent', $row);
        $this->assertArrayHasKey('clicks', $row);
        $this->assertArrayHasKey('unique_clicks', $row);

        return $row['url'];
    }

    /**
     * Return the recent history (hourly stats for the last 30 days) for a url
     *
     * @depends testGetUrlsList
     */
    public function testGetTimeSeries($url)
    {
        $response = $this->service->timeSeries($url);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('user_tag', $row);
        $this->assertArrayHasKey('sent', $row);
        $this->assertArrayHasKey('clicks', $row);
        $this->assertArrayHasKey('unique_clicks', $row);
        $this->assertArrayHasKey('time', $row);
    }

    /**
     * Return the 100 most clicked URLs that match the search query given
     *
     * @depends testGetUrlsList
     */
    public function testGetUrlsSearchResults($url)
    {
        $q = str_replace('www.', '', parse_url($url, PHP_URL_HOST));

        $response = $this->service->search($q);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('url', $row);
        $this->assertArrayHasKey('sent', $row);
        $this->assertArrayHasKey('clicks', $row);
        $this->assertArrayHasKey('unique_clicks', $row);
    }

    /**
     * Add a tracking domain to your account
     */
    public function testAddTrackingDomain()
    {
        $domain = DOMAIN_NAME;

        $response = $this->service->addTrackingDomain($domain);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('domain', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('last_tested_at', $response);
        $this->assertArrayHasKey('cname', $response);
        $this->assertArrayHasKey('valid_tracking', $response);

        return $response['domain'];
    }

    /**
     * Checks the CNAME settings for a tracking domain.
     * The domain must have been added already with the
     * add-tracking-domain call
     *
     * @depends testAddTrackingDomain
     */
    public function testCheckTrackingDomain($domain)
    {
        $response = $this->service->checkTrackingDomain($domain);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('domain', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('last_tested_at', $response);
        $this->assertArrayHasKey('cname', $response);
        $this->assertArrayHasKey('valid_tracking', $response);
    }

    /**
     * Get the list of tracking domains set up for this account
     *
     * @depends testAddTrackingDomain
     */
    public function testGetTrackingDomains()
    {
        $response = $this->service->trackingDomains();

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('domain', $row);
        $this->assertArrayHasKey('created_at', $row);
        $this->assertArrayHasKey('last_tested_at', $row);
        $this->assertArrayHasKey('cname', $row);
        $this->assertArrayHasKey('valid_tracking', $row);

        return $response;
    }
}
?>