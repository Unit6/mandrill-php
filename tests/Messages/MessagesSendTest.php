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
 * Tests for sending messages.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class MessagesSendTest extends \PHPUnit_Framework_TestCase
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

    public function testSendRawMessage()
    {
        $subject = 'MessagesSendTest - testSendRawMessage';
        $text = 'This is a raw message test.';
        $from = 'john.smith@example.org';
        $to = RECIPIENT_EMAIL;

        $rawMessage = "From: ${from}\nTo: ${to}\nSubject: ${subject}\n\n${text}";

        $message = new Model\Message();

        $message->setRawMessage($rawMessage);

        $response = $this->service->send($message);

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('email', $row);
        $this->assertArrayHasKey('status', $row);
        $this->assertArrayHasKey('_id', $row);
        $this->assertArrayHasKey('reject_reason', $row);

        $this->assertEquals($to, $row['email']);
        $this->assertEquals(Model\Recipient::STATUS_SENT, $row['status']);
    }

    public function testNewMessage()
    {
        $message = new Model\Message(array(
            'subject'    => 'MessagesSendTest - testNewMessage',
            'from_email' => 'john.smith@example.org',
            'from_name'  => 'John Smith',
            'html'       => '<p><b>Company:</b> *|COMPANY|*</p>'
                          . '<p><b>Name:</b> *|NAME|*</p>'
                          . '<p><b>Link:</b> *|LINK|*</p>',
            'text'       => 'Company: *|COMPANY|*' . PHP_EOL
                          . 'Name: *|NAME|*' . PHP_EOL
                          . 'Link: *|LINK|*' . PHP_EOL,
        ));

        $message->addGlobalMergeVar('company', 'Example Co. Ltd');
        $message->addTag('test-tag');
        $message->addTag('test-delete-tag');
        $message->addGoogleAnalyticsDomain('example.org');
        $message->addHeader('Reply-To', 'jane.doe@example.org');
        $message->addMetadata('website', 'www.example.org');

        #$message->setSubaccount('customer-123');
        #$message->setBccAddress('message.bcc_address@example.org');

        $message->setSendOptions(array(
            'async'   => false,
            'ip_pool' => 'Main Pool',
            #'send_at' => date('Y-m-d H:i:s')
        ));

        return $message;
    }

    /**
     * @depends testNewMessage
     */
    public function testAddingAttachmentToMessage(Model\Message $message)
    {
        $attachment = new Model\Media();

        $attachment->fromFile(ATTACHMENT_PATH);

        $data = $attachment->getData();

        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('content', $data);

        $message->addAttachment($attachment);

        return $message;
    }

    /**
     * @depends testAddingAttachmentToMessage
     */
    public function testMessageAttachmentIsAdded(Model\Message $message)
    {
        $attachments = $message->getAttachments();

        $data = $attachments[0];

        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('content', $data);

        return $message;
    }

    /**
     * @depends testMessageAttachmentIsAdded
     */
    public function testAddingImageToMessage(Model\Message $message)
    {
        $image = new Model\Media();

        $image->fromFile(IMAGE_PATH);

        $data = $image->getData();

        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('content', $data);

        $message->addImage($image);

        // append image CID to HTML.
        $html = $message->getHtml();
        $html .= '<br><br><img src="cid:' . $image->getName() . '">';
        $message->setHtml($html);

        return $message;
    }

    /**
     * @depends testAddingImageToMessage
     */
    public function testMessageImageIsAdded(Model\Message $message)
    {
        $this->assertRegExp('/cid:/', $message->getHtml());

        $images = $message->getImages();

        $data = $images[0];

        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('content', $data);

        return $message;
    }

    /**
     * @depends testMessageImageIsAdded
     */
    public function testAddingRecipientToMessage(Model\Message $message)
    {
        $recipient = new Model\Recipient(array(
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

        $recipient->setTypeTo();

        $data = $recipient->getData();

        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('type', $data);

        $this->assertEquals(Model\Recipient::TYPE_TO, $data['type']);

        $message->addRecipient($recipient);

        return $message;
    }

    /**
     * @depends testAddingRecipientToMessage
     */
    public function testMessageRecipientIsAdded(Model\Message $message)
    {
        $to = $message->getTo();

        $data = $to[0];

        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('type', $data);

        $this->assertEquals(Model\Recipient::TYPE_TO, $data['type']);

        return $message;
    }

    /**
     * @depends testMessageRecipientIsAdded
     */
    public function testSendingOfMessage(Model\Message $message)
    {
        $response = $this->service->send($message);

        $this->assertCount(1, $response);

        $result = $response[0];

        $this->assertArrayHasKey('_id', $result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('status', $result);

        $this->assertEquals(Model\Recipient::STATUS_QUEUED, $result['status']);
    }
}
?>