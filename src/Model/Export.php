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
 * Mandrill Export Model Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Export extends Mandrill\Model
{
    const TYPE_ACTIVITY  = 'activity';
    const TYPE_REJECT    = 'reject';
    const TYPE_WHITELIST = 'whitelist';

    const STATE_COMPLETE = 'complete';
    const STATE_ERROR    = 'error';
    const STATE_EXPIRED  = 'expired';
    const STATE_WAITING  = 'waiting';
    const STATE_WORKING  = 'working';

    public function __construct(array $row = array())
    {
        $this->assignData($row);
    }
}