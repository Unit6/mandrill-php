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
 * Mandrill Message Query Model Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class MessageQuery extends Mandrill\Model
{
    protected $isTimeSeries = false;

    public function __construct(array $row = array())
    {
        $this->assignData($row);
    }

    public function useTimeSeries()
    {
        $this->isTimeSeries = true;
    }

    public function isTimeSeries()
    {
        return $this->isTimeSeries;
    }

    public function setTags($term)
    {
        $this->data['tags'] = (array) $term;
    }

    public function setSenders($term)
    {
        $this->data['senders'] = (array) $term;
    }

    public function setApiKeys($term)
    {
        $this->data['api_keys'] = (array) $term;
    }

    private function parseQueryTerm($prefix, $term)
    {
        $glue = ' AND ' . $prefix . ':';

        $term = (array) $term;

        $term = $prefix . ':' . implode($glue, $term);

        $this->setQuery($term);
    }

    public function bySubject($term)
    {
        $this->parseQueryTerm('subject', $term);
    }

    public function bySender($term)
    {
        $this->parseQueryTerm('sender', $term);
    }

    public function byEmailDomain($term)
    {
        $this->parseQueryTerm('email', $term);
    }

    public function byEmail($term)
    {
        $this->parseQueryTerm('full_email', $term);
    }

    public function byTags($term)
    {
        $this->parseQueryTerm('tags' . $term);
    }
}