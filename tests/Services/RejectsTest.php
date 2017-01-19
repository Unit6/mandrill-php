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
 * Tests for adding, listing, removing an email to rejection blacklist.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class RejectsTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Rejects($client);
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    /**
     * Adds an email to your email rejection blacklist.
     * Addresses that you add manually will never expire
     * and there is no reputation penalty for removing
     * them from your blacklist. Attempting to blacklist
     * an address that has been whitelisted will have no effect.
     */
    public function testAddAddressToRejects()
    {
        $address = new Model\Address(array(
            'email' => 'blacklisted.recipient@example.org',
            'comment' => 'Comment for blacklisting.',
            'subaccount' => null
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
     * Retrieves your email rejection blacklist. You can
     * provide an email address to limit the results. Returns
     * up to 1000 results. By default, entries that have
     * expired are excluded from the results; set include_expired
     * to true to include them.
     *
     * @depends testAddAddressToRejects
     */
    public function testGetListOfRejects($email)
    {
        $include_expired = true;

        $response = $this->service->getList($email, $include_expired);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('reason', $row);
        $this->assertArrayHasKey('detail', $row);
        $this->assertArrayHasKey('last_event_at', $row);
        $this->assertArrayHasKey('email', $row);
        $this->assertArrayHasKey('created_at', $row);
        $this->assertArrayHasKey('expires_at', $row);
        $this->assertArrayHasKey('expired', $row);
        $this->assertArrayHasKey('subaccount', $row);
        $this->assertArrayHasKey('sender', $row);

        $this->assertEquals($email, $row['email']);
    }

    /**
     * Deletes an email rejection. There is no limit to how many
     * rejections you can remove from your blacklist, but keep
     * in mind that each deletion has an affect on your reputation.
     *
     * @depends testAddAddressToRejects
     */
    public function testDeleteAddressFromRejects($email)
    {
        $response = $this->service->delete($email);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('email', $response);
        $this->assertArrayHasKey('subaccount', $response);
        $this->assertArrayHasKey('deleted', $response);

        $this->assertEquals($email, $response['email']);
        $this->assertTrue($response['deleted']);
    }
}
?>