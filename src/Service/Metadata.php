<?php
/*
 * This file is part of the Mandrill package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Mandrill\Service;

use Unit6\Mandrill;
use Unit6\Mandrill\Model;
use Unit6\Mandrill\Exception;

/**
 * Mandrill Metadata Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Metadata extends Mandrill\Service
{
    /**
     * Constructs the internal representation of the Mandrill Metadata service.
     */
    public function __construct(Mandrill\Client $client)
    {
        parent::__construct($client);
    }

    /**
     * Get the list of custom metadata fields indexed for the account.
     *
     * @return array the custom metadata fields for the account
     *     - return[] struct the individual custom metadata field info
     *         - name string the unique identifier of the metadata field to update
     *         - state string the current state of the metadata field, one of "active", "delete", or "index"
     *         - view_template string Mustache template to control how the metadata is rendered in your activity log
     */
    public function getList()
    {
        return $this->client->request('metadata/list');
    }

    /**
     * Add a new custom metadata field to be indexed for the account.
     *
     * @param Model\Metadata $metadata Instance of Metadata.
     *
     * @return struct the information saved about the new metadata field
     *     - name string the unique identifier of the metadata field to update
     *     - state string the current state of the metadata field, one of "active", "delete", or "index"
     *     - view_template string Mustache template to control how the metadata is rendered in your activity log
     */
    public function add(Model\Metadata $metadata)
    {
        $params = $metadata->getData();

        return $this->client->request('metadata/add', $params);
    }

    /**
     * Update an existing custom metadata field.
     *
     * @param Model\Metadata $metadata Instance of Metadata.
     *
     * @return struct the information for the updated metadata field
     *     - name string the unique identifier of the metadata field to update
     *     - state string the current state of the metadata field, one of "active", "delete", or "index"
     *     - view_template string Mustache template to control how the metadata is rendered in your activity log
     */
    public function update(Model\Metadata $metadata)
    {
        $params = $metadata->getData();

        return $this->client->request('metadata/update', $params);
    }

    /**
     * Delete an existing custom metadata field. Deletion isn't instataneous,
     * and /metadata/list will continue to return the field until the
     * asynchronous deletion process is complete.
     *
     * @param string $name the unique identifier of the metadata field to update
     *
     * @return struct the information for the deleted metadata field
     *     - name string the unique identifier of the metadata field to update
     *     - state string the current state of the metadata field, one of "active", "delete", or "index"
     *     - view_template string Mustache template to control how the metadata is rendered in your activity log
     */
    public function delete($name)
    {
        $params = array(
            'name' => $name
        );

        return $this->client->request('metadata/delete', $params);
    }
}