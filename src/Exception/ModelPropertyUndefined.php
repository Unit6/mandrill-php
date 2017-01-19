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
 * An undefined property for a Model is called.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class ModelPropertyUndefined extends Mandrill\Exception
{
    public function __construct($name)
    {
        $message = 'Undefined property in Model class.' . PHP_EOL
         . 'Name: ' . $name;

        parent::__construct($message, $code = 0);
    }
}