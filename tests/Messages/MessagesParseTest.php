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
 * Tests for parsing the full MIME document for an email message.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class MessagesParseTest extends \PHPUnit_Framework_TestCase
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

    public function testGetMessageParts()
    {
        $subject = 'Some Subject';
        $text = 'Some content.';
        $from = 'sender@example.com';
        $to = 'recipient.email@example.org';

        $rawMessage = "From: ${from}\nTo: ${to}\nSubject: ${subject}\n\n${text}";

        $response = $this->service->parse($rawMessage);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('headers', $response);
        $this->assertArrayHasKey('text', $response);
        $this->assertArrayHasKey('from_email', $response);
        $this->assertArrayHasKey('to', $response);
        $this->assertArrayHasKey('subject', $response);

        $this->assertEquals($subject, $response['subject']);
        $this->assertEquals($text, $response['text']);
        $this->assertEquals($from, $response['from_email']);
        $this->assertEquals($to, $response['to'][0]['email']);
    }
}
?>