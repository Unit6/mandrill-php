<?php
/*
 * This file is part of the Mandrill package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Mandrill\Model;

use Unit6\Mandrill;
use Unit6\Mandrill\Exception;

/**
 * Mandrill Webhook Model Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Webhook extends Mandrill\Model
{
    public function __construct(array $row = array())
    {
        $this->assignData($row);
    }

    public function addEvent($name)
    {
        $this->appendToList('events', $name);
    }
}