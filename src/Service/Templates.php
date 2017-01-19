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
 * Mandrill Templates Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Templates extends Mandrill\Service
{
    /**
     * Constructs the internal representation of the Mandrill Templates service.
     */
    public function __construct(Mandrill\Client $client)
    {
        parent::__construct($client);
    }

    /**
     * Add a new template
     *
     * @param Model\Template $template The information on the template to add
     *
     * @return struct the information saved about the new template
     *     - slug string the immutable unique code name of the template
     *     - name string the name of the template
     *     - labels array the list of labels applied to the template
     *         - labels[] string a single label
     *     - code string the full HTML code of the template, with mc:edit attributes marking the editable elements - draft version
     *     - subject string the subject line of the template, if provided - draft version
     *     - from_email string the default sender address for the template, if provided - draft version
     *     - from_name string the default sender from name for the template, if provided - draft version
     *     - text string the default text part of messages sent with the template, if provided - draft version
     *     - publish_name string the same as the template name - kept as a separate field for backwards compatibility
     *     - publish_code string the full HTML code of the template, with mc:edit attributes marking the editable elements that are available as published, if it has been published
     *     - publish_subject string the subject line of the template, if provided
     *     - publish_from_email string the default sender address for the template, if provided
     *     - publish_from_name string the default sender from name for the template, if provided
     *     - publish_text string the default text part of messages sent with the template, if provided
     *     - published_at string the date and time the template was last published as a UTC string in YYYY-MM-DD HH:MM:SS format, or null if it has not been published
     *     - created_at string the date and time the template was first created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - updated_at string the date and time the template was last modified as a UTC string in YYYY-MM-DD HH:MM:SS format
     */
    public function add(Model\Template $template)
    {
        $params = $template->getData();

        return $this->client->request('templates/add', $params);
    }

    /**
     * Get the information for an existing template
     *
     * @param string $name the immutable name of an existing template
     *
     * @return struct the requested template information
     *     - slug string the immutable unique code name of the template
     *     - name string the name of the template
     *     - labels array the list of labels applied to the template
     *         - labels[] string a single label
     *     - code string the full HTML code of the template, with mc:edit attributes marking the editable elements - draft version
     *     - subject string the subject line of the template, if provided - draft version
     *     - from_email string the default sender address for the template, if provided - draft version
     *     - from_name string the default sender from name for the template, if provided - draft version
     *     - text string the default text part of messages sent with the template, if provided - draft version
     *     - publish_name string the same as the template name - kept as a separate field for backwards compatibility
     *     - publish_code string the full HTML code of the template, with mc:edit attributes marking the editable elements that are available as published, if it has been published
     *     - publish_subject string the subject line of the template, if provided
     *     - publish_from_email string the default sender address for the template, if provided
     *     - publish_from_name string the default sender from name for the template, if provided
     *     - publish_text string the default text part of messages sent with the template, if provided
     *     - published_at string the date and time the template was last published as a UTC string in YYYY-MM-DD HH:MM:SS format, or null if it has not been published
     *     - created_at string the date and time the template was first created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - updated_at string the date and time the template was last modified as a UTC string in YYYY-MM-DD HH:MM:SS format
     */
    public function info($name)
    {
        $params = array(
            'name' => $name
        );

        return $this->client->request('templates/info', $params);
    }

    /**
     * Update the code for an existing template. If null is provided
     * for any fields, the values will remain unchanged.
     *
     * @param Model\Template $template The information on the template to add
     *
     * @return struct the template that was updated
     *     - slug string the immutable unique code name of the template
     *     - name string the name of the template
     *     - labels array the list of labels applied to the template
     *         - labels[] string a single label
     *     - code string the full HTML code of the template, with mc:edit attributes marking the editable elements - draft version
     *     - subject string the subject line of the template, if provided - draft version
     *     - from_email string the default sender address for the template, if provided - draft version
     *     - from_name string the default sender from name for the template, if provided - draft version
     *     - text string the default text part of messages sent with the template, if provided - draft version
     *     - publish_name string the same as the template name - kept as a separate field for backwards compatibility
     *     - publish_code string the full HTML code of the template, with mc:edit attributes marking the editable elements that are available as published, if it has been published
     *     - publish_subject string the subject line of the template, if provided
     *     - publish_from_email string the default sender address for the template, if provided
     *     - publish_from_name string the default sender from name for the template, if provided
     *     - publish_text string the default text part of messages sent with the template, if provided
     *     - published_at string the date and time the template was last published as a UTC string in YYYY-MM-DD HH:MM:SS format, or null if it has not been published
     *     - created_at string the date and time the template was first created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - updated_at string the date and time the template was last modified as a UTC string in YYYY-MM-DD HH:MM:SS format
     */
    public function update(Model\Template $template)
    {
        $params = $template->getData();

        return $this->client->request('templates/update', $params);
    }

    /**
     * Publish the content for the template. Any new messages sent using this template will start using the content that was previously in draft.
     *
     * @param string $name the immutable name of an existing template
     *
     * @return struct the template that was published
     *     - slug string the immutable unique code name of the template
     *     - name string the name of the template
     *     - labels array the list of labels applied to the template
     *         - labels[] string a single label
     *     - code string the full HTML code of the template, with mc:edit attributes marking the editable elements - draft version
     *     - subject string the subject line of the template, if provided - draft version
     *     - from_email string the default sender address for the template, if provided - draft version
     *     - from_name string the default sender from name for the template, if provided - draft version
     *     - text string the default text part of messages sent with the template, if provided - draft version
     *     - publish_name string the same as the template name - kept as a separate field for backwards compatibility
     *     - publish_code string the full HTML code of the template, with mc:edit attributes marking the editable elements that are available as published, if it has been published
     *     - publish_subject string the subject line of the template, if provided
     *     - publish_from_email string the default sender address for the template, if provided
     *     - publish_from_name string the default sender from name for the template, if provided
     *     - publish_text string the default text part of messages sent with the template, if provided
     *     - published_at string the date and time the template was last published as a UTC string in YYYY-MM-DD HH:MM:SS format, or null if it has not been published
     *     - created_at string the date and time the template was first created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - updated_at string the date and time the template was last modified as a UTC string in YYYY-MM-DD HH:MM:SS format
     */
    public function publish($name)
    {
        $params = array(
            'name' => $name
        );

        return $this->client->request('templates/publish', $params);
    }

    /**
     * Delete a template
     *
     *  @param string $name the immutable name of an existing template
     *
     * @return struct the template that was deleted
     *     - slug string the immutable unique code name of the template
     *     - name string the name of the template
     *     - labels array the list of labels applied to the template
     *         - labels[] string a single label
     *     - code string the full HTML code of the template, with mc:edit attributes marking the editable elements - draft version
     *     - subject string the subject line of the template, if provided - draft version
     *     - from_email string the default sender address for the template, if provided - draft version
     *     - from_name string the default sender from name for the template, if provided - draft version
     *     - text string the default text part of messages sent with the template, if provided - draft version
     *     - publish_name string the same as the template name - kept as a separate field for backwards compatibility
     *     - publish_code string the full HTML code of the template, with mc:edit attributes marking the editable elements that are available as published, if it has been published
     *     - publish_subject string the subject line of the template, if provided
     *     - publish_from_email string the default sender address for the template, if provided
     *     - publish_from_name string the default sender from name for the template, if provided
     *     - publish_text string the default text part of messages sent with the template, if provided
     *     - published_at string the date and time the template was last published as a UTC string in YYYY-MM-DD HH:MM:SS format, or null if it has not been published
     *     - created_at string the date and time the template was first created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - updated_at string the date and time the template was last modified as a UTC string in YYYY-MM-DD HH:MM:SS format
     */
    public function delete($name)
    {
        $params = array(
            'name' => $name
        );

        return $this->client->request('templates/delete', $params);
    }

    /**
     * Return a list of all the templates available to this user
     *
     * @param string $label an optional label to filter the templates
     *
     * @return array an array of structs with information about each template
     *     - return[] struct the information on each template in the account
     *         - slug string the immutable unique code name of the template
     *         - name string the name of the template
     *         - labels array the list of labels applied to the template
     *             - labels[] string a single label
     *         - code string the full HTML code of the template, with mc:edit attributes marking the editable elements - draft version
     *         - subject string the subject line of the template, if provided - draft version
     *         - from_email string the default sender address for the template, if provided - draft version
     *         - from_name string the default sender from name for the template, if provided - draft version
     *         - text string the default text part of messages sent with the template, if provided - draft version
     *         - publish_name string the same as the template name - kept as a separate field for backwards compatibility
     *         - publish_code string the full HTML code of the template, with mc:edit attributes marking the editable elements that are available as published, if it has been published
     *         - publish_subject string the subject line of the template, if provided
     *         - publish_from_email string the default sender address for the template, if provided
     *         - publish_from_name string the default sender from name for the template, if provided
     *         - publish_text string the default text part of messages sent with the template, if provided
     *         - published_at string the date and time the template was last published as a UTC string in YYYY-MM-DD HH:MM:SS format, or null if it has not been published
     *         - created_at string the date and time the template was first created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *         - updated_at string the date and time the template was last modified as a UTC string in YYYY-MM-DD HH:MM:SS format
     */
    public function getList($label = null)
    {
        $params = array(
            'label' => $label
        );

        return $this->client->request('templates/list', $params);
    }

    /**
     * Return the recent history (hourly stats for the last 30 days) for a template
     *
     * @param string $name the name of an existing template
     *
     * @return array the array of history information
     *     - return[] struct the stats for a single hour
     *         - time string the hour as a UTC date string in YYYY-MM-DD HH:MM:SS format
     *         - sent integer the number of emails that were sent during the hour
     *         - hard_bounces integer the number of emails that hard bounced during the hour
     *         - soft_bounces integer the number of emails that soft bounced during the hour
     *         - rejects integer the number of emails that were rejected during the hour
     *         - complaints integer the number of spam complaints received during the hour
     *         - opens integer the number of emails opened during the hour
     *         - unique_opens integer the number of unique opens generated by messages sent during the hour
     *         - clicks integer the number of tracked URLs clicked during the hour
     *         - unique_clicks integer the number of unique clicks generated by messages sent during the hour
     */
    public function timeSeries($name)
    {
        $params = array(
            'name' => $name
        );

        return $this->client->request('templates/time-series', $params);
    }

    /**
     * Inject content and optionally merge fields into a template,
     * returning the HTML that results
     *
     * @param Model\Template $template The information on the template to render
     *
     * @return struct the result of rendering the given template with the content and merge field values injected
     *     - html string the rendered HTML as a string
     */
    public function render(Model\Template $template)
    {
        $params = array(
            'template_name' => $template->getName(),
            'template_content' => $template->getContent(),
            'merge_vars' => $template->getMergeVars()
        );

        return $this->client->request('templates/render', $params);
    }
}