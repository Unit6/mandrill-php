<?php
/*
 * This file is part of the Mandrill package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

    require realpath(dirname(__FILE__) . '/../autoload.php');
    require realpath(dirname(__FILE__) . '/../tests/Bootstrap.php');

    use Unit6\Mandrill;

    $image = new Mandrill\Model\Media();

    try {
        $image->fromFile(IMAGE_PATH);
    } catch (Mandrill\Exception\MediaFileNotFound $e) {
        echo $e->getMessage() . PHP_EOL;
        exit;
    }

    #var_dump($image->getName(), $image->getType()); exit;

    $attachment = new Mandrill\Model\Media();

    try {
        $attachment->fromFile(ATTACHMENT_PATH);
    } catch (Mandrill\Exception\MediaFileNotFound $e) {
        echo $e->getMessage() . PHP_EOL;
        exit;
    }

    #var_dump($attachment->getName(), $attachment->getType()); exit;

    $recipient = new Mandrill\Model\Recipient(array(
        'email' => 'j.doe@example.org',
        'name' => 'Jane Doe',
        'metadata' => array(
            'user_id' => 123456
        ),
        'merge_vars' => array(
            'name' => 'Jane Doe',
            'link' => 'http://www.example.org/jane-doe'
        ),
    ));

    $recipient->setTypeTo();

    #var_dump($recipient); exit;

    $message = new Mandrill\Model\Message(array(
        'subject'    => 'Sales Enquiry',
        'from_email' => 'sales@example.org',
        'from_name'  => 'Sales Team',
        'html'       => '<p>Example <i>HTML</i> content</p>'
                      . '<p><b>Name:</b> *|NAME|*</p>'
                      . '<p><b>Link:</b> *|LINK|*</p>'
                      . '<p><b>Website:</b> *|WEBSITE|*</p>'
                      . '<br><br><img src="cid:' . $image->getName() . '">',
        'text'       => 'Example text content' . PHP_EOL
                      . 'Name: *|NAME|*' . PHP_EOL
                      . 'Link: *|LINK|*' . PHP_EOL
                      . 'Website: *|WEBSITE|*' . PHP_EOL,
    ));

    $message->addGlobalMergeVar('website', 'http://www.example.org');
    $message->addRecipient($recipient);
    $message->addHeader('Reply-To', 'customers@example.org');
    #$message->setSubaccount('foobar');
    #$message->setBccAddress('bcc@example.org');
    $message->addAttachment($attachment);
    $message->addImage($image);

    $message->setSendOptions(array(
        'async'   => false,
        'ip_pool' => 'Main Pool',
        #'send_at' => date('Y-m-d H:i:s')
    ));

    #var_dump($message); exit;

    $client = new Mandrill\Client($config);

    $messages = new Mandrill\Service\Messages($client);

    try {
        $response = $messages->send($message);

    } catch (Mandrill\Exception  $e) {

        echo 'A Mandrill error occurred: ' . PHP_EOL
            . 'Exception: ' . get_class($e) . PHP_EOL
            . 'Message: ' . $e->getMessage() . PHP_EOL;
        exit;

    }

    var_dump($response);

    exit;
