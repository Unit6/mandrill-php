<?php
/*
 * This file is part of the Mandrill package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

#require 'vendor/autoload.php';

$config = array(
    'key'   => '', // test
    'debug' => false,
);

define('SCHEDULED_MESSAGES', false);


define('RECIPIENT_NAME',  'John Smith');
define('RECIPIENT_EMAIL', 'j.smith@example.org');
#define('RECIPIENT_NAME',  'Recipient Name');
#define('RECIPIENT_EMAIL', 'recipient.email@example.org');
define('RECIPIENT_ID',    '123456');

define('TEMPLATE_NAME', 'Test Template');
define('DOMAIN_NAME', 'track.example.org');
define('MESSAGE_ID', '11e1e089ee994fb492d629b5bd82ee88');


define('IMAGE_PATH', realpath(dirname(__FILE__) . '/files/mandrill.jpg'));
define('ATTACHMENT_PATH', realpath(dirname(__FILE__) . '/files/mandrill-face.png'));
