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
 * Tests for adding, listing and deleting an email to rejection whitelist.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class WhitelistsTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Whitelists($client);
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    /**
     * Adds an email to your email rejection whitelist. If
     * the address is currently on your blacklist, that blacklist
     * entry will be removed automatically.
     */
    public function testAddAddressToWhitelist()
    {
        $address = new Model\Address(array(
            'email' => 'whitelisted.recipient@example.org',
            'comment' => 'Comment for blacklisting.',
        ));

        $response = $this->service->add($address);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('email', $response);
        $this->assertArrayHasKey('added', $response);

        $this->assertEquals($address->getEmail(), $response['email']);
        $this->assertTrue($response['added']);

        return $response['email'];
    }

    /**
     * Retrieves your email rejection whitelist. You can provide
     * an email address or search prefix to limit the results.
     * Returns up to 1000 results.
     *
     * @depends testAddAddressToWhitelist
     */
    public function testGetListOfWhitelistedAddresses($email)
    {
        $response = $this->service->getList($email);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('email', $row);
        $this->assertArrayHasKey('detail', $row);
        $this->assertArrayHasKey('created_at', $row);

        $this->assertEquals($email, $row['email']);
    }

    /**
     * Removes an email address from the whitelist.
     *
     * @depends testAddAddressToWhitelist
     */
    public function testDeleteAddressFromWhitelist($email)
    {
        $response = $this->service->delete($email);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('email', $response);
        $this->assertArrayHasKey('deleted', $response);

        $this->assertEquals($email, $response['email']);
        $this->assertTrue($response['deleted']);
    }
}
?>