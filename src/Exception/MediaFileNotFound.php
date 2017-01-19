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
 * The media file hasn't been found.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class MediaFileNotFound extends Mandrill\Exception
{
    public function __construct($path, $code = 0)
    {
        $message = 'The media file cound not be found:' . PHP_EOL
            . 'Path: ' . $path;

        parent::__construct($message, $code);
    }
}