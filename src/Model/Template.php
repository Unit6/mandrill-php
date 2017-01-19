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
 * Mandrill Template Model Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Template extends Mandrill\Model
{
    public function __construct(array $row = array())
    {
        $this->assignData($row);
    }

    /**
     * Adding dynamic content within a template using
     * editble regions defined as mc:edit="section-name"
     *
     * https://mandrill.zendesk.com/hc/en-us/articles/205582497
     *
     */
    public function addContent($name, $content)
    {
        $item = array(
            'name' => $name,
            'content' => $content
        );

        $this->appendToList('content', $item);
    }

    public function addMergeVar($name, $content)
    {
        $item = array(
            'name' => $name,
            'content' => $content
        );

        $this->appendToList('merge_vars', $item);
    }

    /*
    public function getData()
    {
        return array(
            'template_name' => $this->getName(),
            'template_content' => $this->getContent()
        );
    }
    */
}