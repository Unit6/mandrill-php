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
 * Tests for getting the information about the API-connected user.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class UsersInfoTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Users($client);
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    /**
     * Return the information about the API-connected user
     */
    public function testGetUserInfo()
    {
        $response = $this->service->info();

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('username', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('public_id', $response);
        $this->assertArrayHasKey('reputation', $response);
        $this->assertArrayHasKey('hourly_quota', $response);
        $this->assertArrayHasKey('backlog', $response);
        $this->assertArrayHasKey('stats', $response);
    }

    /**
     * Validate an API key and respond to a ping
     */
    public function testUserPing()
    {
        $response = $this->service->ping();

        $this->assertNotEmpty($response);

        $this->assertEquals('PONG!', $response);
    }

    /**
     * Validate an API key and respond to a ping (anal JSON parser version)
     */
    public function testUserPingWithJsonFlag()
    {
        $response = $this->service->ping($json = true);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('PING', $response);
        $this->assertEquals('PONG!', $response['PING']);
    }

    /**
     * Return the senders that have tried to use this account,
     * both verified and unverified
     */
    public function testGetUserSenders()
    {
        $response = $this->service->senders();

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
    }
}
?>