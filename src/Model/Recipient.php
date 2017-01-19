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
 * Mandrill Recipient Model Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Recipient extends Mandrill\Model
{
    const TYPE_TO  = 'to';
    const TYPE_CC  = 'cc';
    const TYPE_BCC = 'bcc';

    // the sending status for a recipient.
    const STATUS_SENT      = 'sent';
    const STATUS_QUEUED    = 'queued';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_INVALID   = 'invalid';

    // the reject_reason when recipient status is rejected.
    const REJECT_HARD_BOUNCE     = 'hard-bounce';
    const REJECT_SOFT_BOUNCE     = 'soft-bounce';
    const REJECT_SPAM            = 'spam';
    const REJECT_UNSUBSCRIBED    = 'unsub';
    const REJECT_CUSTOM          = 'custom';
    const REJECT_INVALID_SENDER  = 'invalid-sender';
    const REJECT_INVALID         = 'invalid';
    const REJECT_TEST_MODE_LIMIT = 'test-mode-limit';
    const REJECT_RULE            = 'rule';

    public function __construct(array $row = array())
    {
        $this->assignData($row);
    }

    public function setTypeTo()
    {
        $this->data['type'] = self::TYPE_TO;
    }

    public function setTypeCc()
    {
        $this->data['type'] = self::TYPE_CC;
    }

    public function setTypeBcc()
    {
        $this->data['type'] = self::TYPE_BCC;
    }

    public function getData()
    {
        $fields = array('email', 'name', 'type');

        $data = array();

        foreach ($fields as $i) {
            if (isset($this->data[$i])) {
                $data[$i] = $this->data[$i];
            }
        }

        return $data;
    }
}