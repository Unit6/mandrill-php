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
 * Tests for sending a message using a template.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class MessagesSendTemplateTest extends \PHPUnit_Framework_TestCase
{
    protected $service;
    protected $recipient;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Messages($client);

        $this->recipient = new Model\Recipient(array(
            'email' => RECIPIENT_EMAIL,
            'name' => RECIPIENT_NAME,
            'metadata' => array(
                'user_id' => RECIPIENT_ID
            ),
            'merge_vars' => array(
                'name' => RECIPIENT_NAME,
                'link' => 'http://www.example.org/' . RECIPIENT_ID
            ),
        ));

        $this->recipient->setTypeTo();
    }

    protected function tearDown()
    {
        unset($this->service);
        unset($this->recipient);
    }

    public function testNewTemplateMessage()
    {
        $message = new Model\Message(array(
            #'subject'    => 'MessagesSendTemplateTest - testNewTemplateMessage',
            'from_email' => 'john.smith@example.org',
            'from_name'  => 'John Smith',
        ));

        $message->addRecipient($this->recipient);

        $message->setSendOptions(array(
            'async'   => false,
            'ip_pool' => 'Main Pool',
            #'send_at' => date('Y-m-d H:i:s')
        ));

        return $message;
    }

    /**
     * @depends testNewTemplateMessage
     */
    public function testAddTemplateToMessage(Model\Message $message)
    {
        $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.';

        $message->addGlobalMergeVar('test_section', $lorem);

        $template = new Model\Template();
        $template->setName('test');
        $template->addContent('test-section', $lorem);

        $message->setTemplate($template);

        $this->assertTrue($message->hasTemplate());

        return $message;
    }

    /**
     * @depends testAddTemplateToMessage
     */
    public function testSendTemplateMessage(Model\Message $message)
    {
        $response = $this->service->send($message);

        $this->assertCount(1, $response);

        $result = $response[0];

        $this->assertArrayHasKey('_id', $result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('reject_reason', $result);

        $this->assertEquals(Model\Recipient::STATUS_SENT, $result['status']);
    }

    /**
     * @depends testNewTemplateMessage
     * @expectedException Unit6\Mandrill\Exception\TemplateUnknown
     */
    public function testSendMessageWithUnknownTemplate(Model\Message $message)
    {
        $template = new Model\Template();
        $template->setName('unknown');

        $message->setTemplate($template);

        $response = $this->service->send($message);
    }
}
?>