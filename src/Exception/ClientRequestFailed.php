<?php
/*
 * This file is part of the Mandrill package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Mandrill\Exception;

use Unit6\Mandrill;

/**
 *
 *
 * @author Unit6 <team@unit6websites.com>
 */
class ClientRequestFailed extends Mandrill\Exception
{
    public function __construct($ch, $url)
    {
        $message = 'Client API request failed: '
            . PHP_EOL . 'Endoint: ' . $url
            . PHP_EOL . 'Error: ' . curl_error($ch);

        parent::__construct($message, $code = 0);
    }
}