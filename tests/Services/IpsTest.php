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
 * Tests for IPs.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class IpsTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Ips($client);
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    private function assertMandrillDedicatedIp($response)
    {
        $this->assertArrayHasKey('ip', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('pool', $response);
        $this->assertArrayHasKey('domain', $response);
        $this->assertArrayHasKey('custom_dns', $response);
        $this->assertArrayHasKey('warmup', $response);
    }

    private function assertMandrillPool($response)
    {
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('ips', $response);
    }

    private function assertMandrillPoolWithIps($response)
    {
        $this->assertMandrillPool($response);

        $this->assertNotEmpty($response['ips']);

        $row = $response['ips'][0];

        $this->assertMandrillDedicatedIp($row);
    }

    public function testCreatePool()
    {
        $pool = 'Example Pool';

        $response = $this->service->createPool($pool);

        $this->assertNotEmpty($response);

        $this->assertMandrillPool($response);

        return $response['name'];
    }

    /**
     * @depends testCreatePool
     */
    public function testProvision($pool)
    {
        $warmup = false;

        try {
            $response = $this->service->provision($warmup, $pool);
        } catch (Exception\PaymentRequired $e) {
            $this->markTestSkipped($e->getMessage());
        }

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('requested_at', $response);
    }

    /**
     * @depends testProvision
     */
    public function testGetListOfDedicatedIps()
    {
        $response = $this->service->getList();

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertMandrillDedicatedIp($row);

        return $row['ip'];
    }

    /**
     * @depends testGetListOfDedicatedIps
     */
    public function testGetDedicatedIpInfo($ip)
    {
        $response = $this->service->info($ip);

        $this->assertNotEmpty($response);

        $this->assertMandrillDedicatedIp($response);
    }

    /**
     * @depends testGetListOfDedicatedIps
     */
    public function testWarmupStart($ip)
    {
        $response = $this->service->startWarmup($ip);

        $this->assertNotEmpty($response);

        $this->assertMandrillDedicatedIp($response);

        return $response['ip'];
    }

    /**
     * @depends testWarmupStart
     * @depends testCreatePool
     */
    public function testSetPool($ip, $pool)
    {
        $create_pool = false;

        $response = $this->service->setPool($ip, $pool, $create_pool);

        $this->assertNotEmpty($response);

        $this->assertMandrillDedicatedIp($response);
    }

    /**
     * @depends testSetPool
     */
    public function testCancelWarmup($ip)
    {
        $response = $this->service->cancelWarmup($ip);

        $this->assertNotEmpty($response);

        $this->assertMandrillDedicatedIp($response);
    }

    /**
     * @depends testGetListOfDedicatedIps
     */
    public function testSetCustomDns($ip)
    {
        $domain = 'mail1.example.mandrillapp.com';

        $response = $this->service->setCustomDns($ip, $domain);

        $this->assertNotEmpty($response);

        $this->assertMandrillDedicatedIp($response);

        return $response;
    }

    /**
     * @depends testSetCustomDns
     */
    public function testCheckCustomDns($dedicatedIp)
    {
        $ip = $dedicatedIp['ip'];
        $domain = $dedicatedIp['domain'];

        $response = $this->service->checkCustomDns($ip, $domain);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('valid', $response);
        $this->assertArrayHasKey('deleted', $response);
    }

    /**
     * @depends testCreatePool
     */
    public function testGetListOfPools()
    {
        $response = $this->service->listPools();

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertMandrillPool($row);

        return $row['name'];
    }

    /**
     * @depends testCreatePool
     */
    public function testGetPoolInfo($pool)
    {
        $response = $this->service->poolInfo($pool);

        $this->assertNotEmpty($response);

        $this->assertMandrillPool($response);
    }

    /**
     * @depends testGetListOfDedicatedIps
     */
    public function testDeleteDedicatedIp($ip)
    {
        $response = $this->service->delete($ip);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('ip', $response);
        $this->assertArrayHasKey('deleted', $response);

        $this->assertTrue($response['deleted']);
    }

    /**
     * @depends testCreatePool
     */
    public function testDeletePool($pool)
    {
        $response = $this->service->deletePool($pool);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('pool', $response);
        $this->assertArrayHasKey('deleted', $response);

        $this->assertTrue($response['deleted']);
    }
}
?>