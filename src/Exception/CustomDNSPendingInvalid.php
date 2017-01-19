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
 * A custom DNS change for this dedicated IP is currently pending.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class CustomDNSPendingInvalid extends Mandrill\Exception
{
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }
}