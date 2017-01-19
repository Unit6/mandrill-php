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
 * Tests for Senders.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class SendersListTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Senders($client);
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    /**
     * Return the senders that have tried to use this account.
     */
    public function testGetListOfSenders()
    {
        $response = $this->service->getList();

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('sent', $row);
        $this->assertArrayHasKey('hard_bounces', $row);
        $this->assertArrayHasKey('soft_bounces', $row);
        $this->assertArrayHasKey('rejects', $row);
        $this->assertArrayHasKey('complaints', $row);
        $this->assertArrayHasKey('unsubs', $row);
        $this->assertArrayHasKey('opens', $row);
        $this->assertArrayHasKey('clicks', $row);
        $this->assertArrayHasKey('unique_opens', $row);
        $this->assertArrayHasKey('unique_clicks', $row);
        $this->assertArrayHasKey('reputation', $row);
        $this->assertArrayHasKey('address', $row);
        $this->assertArrayHasKey('created_at', $row);

        return $row['address'];
    }

    /**
     * Return more detailed information about a single sender,
     * including aggregates of recent stats.
     *
     * @depends testGetListOfSenders
     */
    public function testGetInfoForSender($address)
    {
        $response = $this->service->info($address);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('sent', $response);
        $this->assertArrayHasKey('hard_bounces', $response);
        $this->assertArrayHasKey('soft_bounces', $response);
        $this->assertArrayHasKey('rejects', $response);
        $this->assertArrayHasKey('complaints', $response);
        $this->assertArrayHasKey('unsubs', $response);
        $this->assertArrayHasKey('opens', $response);
        $this->assertArrayHasKey('clicks', $response);
        $this->assertArrayHasKey('unique_opens', $response);
        $this->assertArrayHasKey('unique_clicks', $response);
        $this->assertArrayHasKey('reputation', $response);
        $this->assertArrayHasKey('address', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('stats', $response);
    }

    /**
     * Return the recent history (hourly stats for the last 30 days)
     * for a sender.
     *
     * @depends testGetListOfSenders
     */
    public function testGetSendersHistory($address)
    {
        $response = $this->service->timeSeries($address);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('sent', $row);
        $this->assertArrayHasKey('hard_bounces', $row);
        $this->assertArrayHasKey('soft_bounces', $row);
        $this->assertArrayHasKey('rejects', $row);
        $this->assertArrayHasKey('complaints', $row);
        $this->assertArrayHasKey('unsubs', $row);
        $this->assertArrayHasKey('opens', $row);
        $this->assertArrayHasKey('clicks', $row);
        $this->assertArrayHasKey('unique_opens', $row);
        $this->assertArrayHasKey('unique_clicks', $row);
        $this->assertArrayHasKey('time', $row);
    }

    /**
     * Adds a sender domain to your account. Sender domains are
     * added automatically as you send, but you can use this call
     * to add them ahead of time.
     */
    public function testAddDomain()
    {
        $domain = 'example.com';

        $response = $this->service->addDomain($domain);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('domain', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('last_tested_at', $response);
        $this->assertArrayHasKey('spf', $response);
        $this->assertArrayHasKey('dkim', $response);
        $this->assertArrayHasKey('verified_at', $response);
        $this->assertArrayHasKey('valid_signing', $response);

        $this->assertEquals($domain, $response['domain']);

        return $response['domain'];
    }

    /**
     * Returns the sender domains that have been added to this account.
     *
     * @depends testAddDomain
     */
    public function testGetListOfDomains()
    {
        $response = $this->service->domains();

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('domain', $row);
        $this->assertArrayHasKey('created_at', $row);
        $this->assertArrayHasKey('last_tested_at', $row);
        $this->assertArrayHasKey('spf', $row);
        $this->assertArrayHasKey('dkim', $row);
        $this->assertArrayHasKey('verified_at', $row);
        $this->assertArrayHasKey('valid_signing', $row);
    }

    /**
     * Checks the SPF and DKIM settings for a domain. If you
     * haven't already added this domain to your account, it
     * will be added automatically.
     *
     * @depends testAddDomain
     */
    public function testCheckDomain($domain)
    {
        $response = $this->service->checkDomain($domain);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('domain', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('last_tested_at', $response);
        $this->assertArrayHasKey('spf', $response);
        $this->assertArrayHasKey('dkim', $response);
        $this->assertArrayHasKey('verified_at', $response);
        $this->assertArrayHasKey('valid_signing', $response);
    }

    /**
     * Sends a verification email in order to verify ownership
     * of a domain. Domain verification is an optional step to
     * confirm ownership of a domain. Once a domain has been
     * verified in a Mandrill account, other accounts may not
     * have their messages signed by that domain unless they
     * also verify the domain. This prevents other Mandrill
     * accounts from sending mail signed by your domain.
     *
     * @depends testAddDomain
     */
    public function testVerifyDomain($domain)
    {
        $mailbox = 'your.name';

        $response = $this->service->verifyDomain($domain, $mailbox);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('domain', $response);
        $this->assertArrayHasKey('email', $response);
    }
}
?>