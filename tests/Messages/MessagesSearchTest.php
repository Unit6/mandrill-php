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
 * Tests for searching recently sent messages with filters.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class MessagesSearchTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Messages($client);
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    public function testMessagesSearchWithLimit()
    {
        $query = new Model\MessageQuery();
        $query->setLimit(1);

        $response = $this->service->search($query);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('ts', $row);
        $this->assertArrayHasKey('subject', $row);
        $this->assertArrayHasKey('email', $row);
        $this->assertArrayHasKey('tags', $row);
        $this->assertArrayHasKey('state', $row);
        $this->assertArrayHasKey('smtp_events', $row);
        $this->assertArrayHasKey('subaccount', $row);
        $this->assertArrayHasKey('resends', $row);
        $this->assertArrayHasKey('reject', $row);
        #$this->assertArrayHasKey('diag', $row);
        #$this->assertArrayHasKey('bgtools_code', $row);
        $this->assertArrayHasKey('_id', $row);
        $this->assertArrayHasKey('sender', $row);
        $this->assertArrayHasKey('template', $row);
        #$this->assertArrayHasKey('bounce_description', $row);
        $this->assertArrayHasKey('opens_detail', $row);
        $this->assertArrayHasKey('clicks_detail', $row);
        $this->assertArrayHasKey('opens', $row);
        $this->assertArrayHasKey('clicks', $row);
    }

    public function testMessagesSearchByEmail()
    {
        $term = RECIPIENT_EMAIL;

        $query = new Model\MessageQuery();
        $query->byEmail($term);
        $query->setLimit(1);

        $response = $this->service->search($query);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('email', $row);
        $this->assertEquals($term, $row['email']);
    }

    public function testMessagesSearchBySubject()
    {
        $term = 'MessagesSendTest';

        $query = new Model\MessageQuery();
        $query->bySubject($term);
        $query->setLimit(1);

        $response = $this->service->search($query);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('subject', $row);
        $this->assertStringStartsWith($term, $row['subject']);
    }

    public function testMessagesSearchByDateRange()
    {
        $term = '2015-04-27';

        $query = new Model\MessageQuery();
        $query->setDateFrom($term);
        $query->setDateTo($term);
        $query->setLimit(1);

        $response = $this->service->search($query);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('ts', $row);
        $this->assertStringStartsWith(date('Y-m-d', $row['ts']), $term);
    }

    public function testMessagesSearchWithDateRangeFilter()
    {
        $term = '2015-04-27';

        $query = new Model\MessageQuery();
        $query->setDateFrom($term);
        $query->setDateTo($term);
        $query->setLimit(1);

        $response = $this->service->search($query);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('ts', $row);
        $this->assertStringStartsWith(date('Y-m-d', $row['ts']), $term);
    }

    public function testMessagesSearchWithTagsFilter()
    {
        $term = 'password-resets';

        $query = new Model\MessageQuery();
        $query->setTags($term);
        $query->setLimit(1);

        $response = $this->service->search($query);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('tags', $row);
        $this->assertTrue(in_array($term, $row['tags']));
    }

    public function testMessagesSearchWithSendersFilter()
    {
        $term = 'john.smith@example.org';

        $query = new Model\MessageQuery();
        $query->setSenders($term);
        $query->setLimit(1);

        $response = $this->service->search($query);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('sender', $row);
        $this->assertEquals($term, $row['sender']);
    }

    public function testMessageSearchTimeSeries()
    {
        $targetEmail = RECIPIENT_EMAIL;
        $targetDate = '2015-04-27';

        $query = new Model\MessageQuery();
        $query->setDateFrom($targetDate);
        $query->setDateTo($targetDate);
        $query->byEmail($targetEmail);
        $query->useTimeSeries();

        $response = $this->service->search($query);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('time', $row);
        $this->assertArrayHasKey('sent', $row);
        $this->assertArrayHasKey('opens', $row);
        $this->assertArrayHasKey('clicks', $row);
        $this->assertArrayHasKey('hard_bounces', $row);
        $this->assertArrayHasKey('soft_bounces', $row);
        $this->assertArrayHasKey('rejects', $row);
        $this->assertArrayHasKey('complaints', $row);
        $this->assertArrayHasKey('unsubs', $row);
        $this->assertArrayHasKey('unique_opens', $row);
        $this->assertArrayHasKey('unique_clicks', $row);
    }
}
?>