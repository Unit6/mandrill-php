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
 * Tests for exports.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class ExportsTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Tags($client);
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    private function assertMandrillTag($response)
    {
        $this->assertArrayHasKey('tag', $response);
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
    }

    private function assertMandrillTagHistory($response)
    {
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
        $this->assertArrayHasKey('time', $response);
    }

    public function testGetListOfTags()
    {
        $response = $this->service->getList();

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertMandrillTag($row);

        return $row['tag'];
    }

    /**
     * @depends testGetListOfTags
     */
    public function testGetTagInfo($tag)
    {
        $response = $this->service->info($tag);

        $this->assertNotEmpty($response);

        $this->assertMandrillTag($response);

        return $response['tag'];
    }

    /**
     * @depends testGetTagInfo
     */
    public function testGetTimeSeriesForTag($tag)
    {
        $response = $this->service->timeSeries($tag);

        $row = $response[0];

        $this->assertMandrillTagHistory($row);
    }

    public function testGetTimeSeriesForAllTags()
    {
        $response = $this->service->timeSeries();

        $row = $response[0];

        $this->assertMandrillTagHistory($row);
    }

    /**
     * @depends testGetTagInfo
     */
    public function testDeleteTag($tag)
    {
        $response = $this->service->delete($tag);

        $this->assertNotEmpty($response);

        $this->assertMandrillTag($response);
    }
}
?>