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
 * Tests for adding, updating, publishing and deleting a webhook.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class WebhooksTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Webhooks($client);
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    public function testAddWebhook()
    {
        $url = 'http://www.example.org/';
        $description = 'My Example Webhook';
        $events = array('send', 'open', 'click');

        $webhook = new Model\Webhook();
        $webhook->setUrl($url);
        $webhook->setDescription($description);
        $webhook->setEvents($events);

        $response = $this->service->add($webhook);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('url', $response);
        $this->assertArrayHasKey('description', $response);
        $this->assertArrayHasKey('events', $response);
        $this->assertArrayHasKey('auth_key', $response);
        $this->assertArrayHasKey('batches_sent', $response);
        $this->assertArrayHasKey('events_sent', $response);
        $this->assertArrayHasKey('created_at', $response);

        $this->assertEquals($url, $response['url']);
        $this->assertEquals($description, $response['description']);
        $this->assertEmpty(array_diff($events, $response['events']));

        return $response['id'];
    }

    /**
     * @depends testAddWebhook
     */
    public function testGetWebhookList()
    {
        $response = $this->service->getList();

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('id', $row);
        $this->assertArrayHasKey('url', $row);
        $this->assertArrayHasKey('description', $row);
        $this->assertArrayHasKey('events', $row);
        $this->assertArrayHasKey('auth_key', $row);
        $this->assertArrayHasKey('batches_sent', $row);
        $this->assertArrayHasKey('events_sent', $row);
        $this->assertArrayHasKey('created_at', $row);
    }

    /**
     * @depends testAddWebhook
     */
    public function testGetWebhookInfo($id)
    {
        $response = $this->service->info($id);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('url', $response);
        $this->assertArrayHasKey('description', $response);
        $this->assertArrayHasKey('events', $response);
        $this->assertArrayHasKey('auth_key', $response);
        $this->assertArrayHasKey('batches_sent', $response);
        $this->assertArrayHasKey('events_sent', $response);
        $this->assertArrayHasKey('created_at', $response);
    }

    /**
     * @depends testAddWebhook
     */
    public function testUpdateWebhook($id)
    {
        $url = 'http://www.example.org/';
        $description = 'My Example Webhook [Updated]';
        $events = array('send', 'open');

        $webhook = new Model\Webhook();
        $webhook->setId($id);
        $webhook->setUrl($url);
        $webhook->setDescription($description);
        $webhook->setEvents($events);

        $response = $this->service->update($webhook);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('url', $response);
        $this->assertArrayHasKey('description', $response);
        $this->assertArrayHasKey('events', $response);
        $this->assertArrayHasKey('auth_key', $response);
        $this->assertArrayHasKey('batches_sent', $response);
        $this->assertArrayHasKey('events_sent', $response);
        $this->assertArrayHasKey('created_at', $response);

        $this->assertEquals($url, $response['url']);
        $this->assertEquals($description, $response['description']);
        $this->assertEmpty(array_diff($events, $response['events']));
    }

    /**
     * @depends testAddWebhook
     */
    public function testDeleteWebhook($id)
    {
        $response = $this->service->delete($id);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('url', $response);
        $this->assertArrayHasKey('description', $response);
        $this->assertArrayHasKey('events', $response);
        $this->assertArrayHasKey('auth_key', $response);
        $this->assertArrayHasKey('batches_sent', $response);
        $this->assertArrayHasKey('events_sent', $response);
        $this->assertArrayHasKey('created_at', $response);

        return $response['id'];
    }

    /**
     * @depends testDeleteWebhook
     * @expectedException Unit6\Mandrill\Exception\WebhookUnknown
     */
    public function testExceptionOnDeleteNonExistingWebhook($id)
    {
        $response = $this->service->delete($id);
    }
}
?>