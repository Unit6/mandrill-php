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
 * Tests for adding, updating, publishing and deleting a subaccount.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class SubaccountsTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Subaccounts($client);
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    public function testAddSubaccount()
    {
        $id = 'example-123';
        $name = 'Example Co. Ltd';
        $notes = 'An example subaccount plan.';
        $quota = 42;

        $subaccount = new Model\Subaccount();
        $subaccount->setId($id);
        $subaccount->setName($name);
        $subaccount->setNotes($notes);
        $subaccount->setCustomQuota($quota);

        $response = $this->service->add($subaccount);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('custom_quota', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('reputation', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('sent_weekly', $response);
        $this->assertArrayHasKey('sent_monthly', $response);
        $this->assertArrayHasKey('sent_total', $response);

        $this->assertEquals($id, $response['id']);
        $this->assertEquals($name, $response['name']);

        return $response['id'];
    }

    /**
     * @depends testAddSubaccount
     */
    public function testGetSubaccountList()
    {
        $response = $this->service->getList();

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('id', $row);
        $this->assertArrayHasKey('name', $row);
        $this->assertArrayHasKey('status', $row);
        $this->assertArrayHasKey('reputation', $row);
        $this->assertArrayHasKey('created_at', $row);
        $this->assertArrayHasKey('first_sent_at', $row);
        $this->assertArrayHasKey('sent_weekly', $row);
        $this->assertArrayHasKey('sent_monthly', $row);
        $this->assertArrayHasKey('sent_total', $row);
    }

    /**
     * @depends testAddSubaccount
     */
    public function testGetSubaccountInfo($id)
    {
        $response = $this->service->info($id);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('custom_quota', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('reputation', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('sent_weekly', $response);
        $this->assertArrayHasKey('sent_monthly', $response);
        $this->assertArrayHasKey('sent_total', $response);
        $this->assertArrayHasKey('sent_hourly', $response);
        $this->assertArrayHasKey('hourly_quota', $response);
        $this->assertArrayHasKey('last_30_days', $response);
    }

    /**
     * @depends testAddSubaccount
     */
    public function testUpdateSubaccount($id)
    {
        $name = 'ACME Co.';
        $notes = 'An example subaccount plan. Pro Edition';
        $quota = 100;

        $subaccount = new Model\Subaccount();
        $subaccount->setId($id);
        $subaccount->setName($name);
        $subaccount->setNotes($notes);
        $subaccount->setCustomQuota($quota);

        $response = $this->service->update($subaccount);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('custom_quota', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('reputation', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('sent_weekly', $response);
        $this->assertArrayHasKey('sent_monthly', $response);
        $this->assertArrayHasKey('sent_total', $response);

        $this->assertEquals($id, $response['id']);
        $this->assertEquals($name, $response['name']);
    }

    /**
     * @depends testAddSubaccount
     */
    public function testPauseSubaccount($id)
    {
        $response = $this->service->pause($id);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('custom_quota', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('reputation', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('sent_weekly', $response);
        $this->assertArrayHasKey('sent_monthly', $response);
        $this->assertArrayHasKey('sent_total', $response);

        return $response['id'];
    }

    /**
     * @depends testPauseSubaccount
     */
    public function testResumeSubaccount($id)
    {
        $response = $this->service->resume($id);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('custom_quota', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('reputation', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('sent_weekly', $response);
        $this->assertArrayHasKey('sent_monthly', $response);
        $this->assertArrayHasKey('sent_total', $response);
    }

    /**
     * @depends testAddSubaccount
     */
    public function testDeleteSubaccount($id)
    {
        $response = $this->service->delete($id);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('custom_quota', $response);
        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('reputation', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('sent_weekly', $response);
        $this->assertArrayHasKey('sent_monthly', $response);
        $this->assertArrayHasKey('sent_total', $response);

        return $response['id'];
    }

    /**
     * @depends testDeleteSubaccount
     * @expectedException Unit6\Mandrill\Exception\SubaccountUnknown
     */
    public function testExceptionOnDeleteNonExistingWebhook($id)
    {
        $response = $this->service->delete($id);
    }
}
?>