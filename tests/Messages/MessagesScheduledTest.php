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
 * Tests for queries on scheduled emails.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class MessagesScheduledTest extends \PHPUnit_Framework_TestCase
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

    public function testGetListOfScheduledMessages()
    {
        if ( ! SCHEDULED_MESSAGES) {
            $this->markTestSkipped(
              'Scheduled messages is not available with this account.'
            );
        }

        $to = RECIPIENT_EMAIL;

        $response = $this->service->listScheduled($to);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('_id', $row);
        $this->assertArrayHasKey('created_at', $row);
        $this->assertArrayHasKey('send_at', $row);
        $this->assertArrayHasKey('from_email', $row);
        $this->assertArrayHasKey('to', $row);
        $this->assertArrayHasKey('subject', $row);

        return $row['_id'];
    }

    /**
     * @depends testGetListOfScheduledMessages
     */
    public function testRescheduleMessage($messageId)
    {
        if ( ! SCHEDULED_MESSAGES) {
            $this->markTestSkipped(
              'Scheduled messages is not available with this account.'
            );
        }

        $send_at = time() + 1000;

        $response = $this->service->reschedule($messageId, $send_at);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('_id', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('send_at', $response);
        $this->assertArrayHasKey('from_email', $response);
        $this->assertArrayHasKey('to', $response);
        $this->assertArrayHasKey('subject', $response);
    }

    /**
     * @depends testGetListOfScheduledMessages
     */
    public function testCancelScheduledMessage($messageId)
    {
        if ( ! SCHEDULED_MESSAGES) {
            $this->markTestSkipped(
              'Scheduled messages is not available with this account.'
            );
        }

        $response = $this->service->cancelScheduled($messageId);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('_id', $response);
        $this->assertArrayHasKey('created_at', $response);
        $this->assertArrayHasKey('send_at', $response);
        $this->assertArrayHasKey('from_email', $response);
        $this->assertArrayHasKey('to', $response);
        $this->assertArrayHasKey('subject', $response);
    }
}
?>