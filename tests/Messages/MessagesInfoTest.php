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
 * Tests for getting the information for a single recently sent message.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class MessagesInfoTest extends \PHPUnit_Framework_TestCase
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

    public function testGetMessageInfo()
    {
        $messageId = MESSAGE_ID;

        $response = $this->service->info($messageId);

        $this->assertNotEmpty($response);
    }
}
?>