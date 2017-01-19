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
 * Tests for an inbound domain and route.
 *
 * An inbound domain must exist before creating an associated route.
 * Delete the route before deleting the domain.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class InboundTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Inbound($client);
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    public function testAddInboundDomain()
    {
        $domain = 'inbound.example.org';

        $response = $this->service->addDomain($domain);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('domain', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('valid_mx', $response);

        return $domain;
    }

    /**
     * @depends testAddInboundDomain
     */
    public function testGetListOfInboundDomains()
    {
        $response = $this->service->domains();

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('domain', $row);
        $this->assertArrayHasKey('created_at', $row);
        $this->assertArrayHasKey('valid_mx', $row);
    }

    /**
     * @depends testAddInboundDomain
     */
    public function testCheckInboundDomain($domain)
    {
        $response = $this->service->checkDomain($domain);

        $this->assertArrayHasKey('domain', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('valid_mx', $response);
    }

    /**
     * @depends testAddInboundDomain
     */
    public function testAddInboundRoute($domain)
    {
        $pattern = 'mailbox-*';
        $url = 'http://www.example.org/';

        $response = $this->service->addRoute($domain, $pattern, $url);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('pattern', $response);
        $this->assertArrayHasKey('url', $response);

        return $response['id'];
    }

    /**
     * @depends testAddInboundDomain
     * @depends testAddInboundRoute
     */
    public function testGetListOfInboundRoutes($domain, $routeId)
    {
        $response = $this->service->routes($domain);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('id', $row);
        $this->assertArrayHasKey('pattern', $row);
        $this->assertArrayHasKey('url', $row);
    }

    /**
     * @depends testAddInboundDomain
     * @depends testAddInboundRoute
     */
    public function testInboundRawDocument($domain, $routeId)
    {
        $helo = 'example.org';
        $client_address = '127.0.0.1';
        $recipient = 'mailbox-123@' . $domain;
        $from = 'sender@' . $helo;

        $raw_message = 'From: ' . $from . '\n'
            . 'To: ' . $recipient . '\n'
            . 'Subject: Test Subject\n\n'
            . 'Test Content.';
        $to = array($recipient);

        $response = $this->service->sendRaw($raw_message, $to, $from, $helo, $client_address);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('email', $row);
        $this->assertArrayHasKey('pattern', $row);
        $this->assertArrayHasKey('url', $row);

        $this->assertEquals($to[0], $row['email']);
    }

    /**
     * @depends testAddInboundRoute
     */
    public function testUpdateInboundRoute($id)
    {
        $pattern = 'reply-*';
        $url = 'http://www.example.org/';

        $response = $this->service->updateRoute($id, $pattern, $url);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('pattern', $response);
        $this->assertArrayHasKey('url', $response);

        $this->assertEquals($pattern, $response['pattern']);
        $this->assertEquals($url, $response['url']);
    }

    /**
     * @depends testAddInboundRoute
     */
    public function testDeleteInboundRoute($id)
    {
        $response = $this->service->deleteRoute($id);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('pattern', $response);
        $this->assertArrayHasKey('url', $response);
    }

    /**
     * @depends testAddInboundDomain
     */
    public function testDeleteInboundDomain($domain)
    {
        $response = $this->service->deleteDomain($domain);

        $this->assertArrayHasKey('domain', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('valid_mx', $response);
    }
}
?>