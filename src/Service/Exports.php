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
 * Mandrill Exports Class.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Exports extends Mandrill\Service
{
    /**
     * Constructs the internal representation of the Mandrill Exports service.
     */
    public function __construct(Mandrill\Client $client)
    {
        parent::__construct($client);
    }

    /**
     * Returns information about an export job. If the export job's state
     * is 'complete', the returned data will include a URL you can use
     * to fetch the results. Every export job produces a zip archive,
     * but the format of the archive is distinct for each job type.
     * The api calls that initiate exports include more details about
     * the output format for that job type.
     *
     * @param string $id an export job identifier
     *
     * @return struct the information about the export
     *     - id string the unique identifier for this Export. Use this identifier when checking the export job's status
     *     - created_at string the date and time that the export job was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - type string the type of the export job - activity, reject, or whitelist
     *     - finished_at string the date and time that the export job was finished as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - state string the export job's state - waiting, working, complete, error, or expired.
     *     - result_url string the url for the export job's results, if the job is completed.
     */
    public function info($id)
    {
        $params = array(
            'id' => $id
        );

        return $this->client->request('exports/info', $params);
    }

    /**
     * Returns a list of your exports.
     *
     * @return array the account's exports
     *     - return[] struct the individual export info
     *         - id string the unique identifier for this Export. Use this identifier when checking the export job's status
     *         - created_at string the date and time that the export job was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *         - type string the type of the export job - activity, reject, or whitelist
     *         - finished_at string the date and time that the export job was finished as a UTC string in YYYY-MM-DD HH:MM:SS format
     *         - state string the export job's state - waiting, working, complete, error, or expired.
     *         - result_url string the url for the export job's results, if the job is completed.
     */
    public function getList()
    {
        return $this->client->request('exports/list');
    }

    /**
     * Begins an export of your rejection blacklist. The blacklist will
     * be exported to a zip archive containing a single file named
     * rejects.csv that includes the following fields: email, reason,
     * detail, created_at, expires_at, last_event_at, expires_at.
     *
     * @param string $notify_email an optional email address to notify when the export job has finished.
     *
     * @return struct information about the rejects export job that was started
     *     - id string the unique identifier for this Export. Use this identifier when checking the export job's status
     *     - created_at string the date and time that the export job was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - type string the type of the export job
     *     - finished_at string the date and time that the export job was finished as a UTC string in YYYY-MM-DD HH:MM:SS format, or null for jobs that have not run
     *     - state string the export job's state
     *     - result_url string the url for the export job's results, if the job is complete
     */
    public function rejects($notify_email = null)
    {
        $params = array(
            'notify_email' => $notify_email
        );

        return $this->client->request('exports/rejects', $params);
    }

    /**
     * Begins an export of your rejection whitelist. The whitelist will
     * be exported to a zip archive containing a single file named
     * whitelist.csv that includes the following fields:
     * email, detail, created_at.
     *
     * @param string $notify_email an optional email address to notify when the export job has finished.
     *
     * @return struct information about the whitelist export job that was started
     *     - id string the unique identifier for this Export. Use this identifier when checking the export job's status
     *     - created_at string the date and time that the export job was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - type string the type of the export job
     *     - finished_at string the date and time that the export job was finished as a UTC string in YYYY-MM-DD HH:MM:SS format, or null for jobs that have not run
     *     - state string the export job's state
     *     - result_url string the url for the export job's results, if the job is complete
     */
    public function whitelist($notify_email = null)
    {
        $params = array(
            'notify_email' => $notify_email
        );

        return $this->client->request('exports/whitelist', $params);
    }

    /**
     * Begins an export of your activity history. The activity will be
     * exported to a zip archive containing a single file named activity.csv
     * in the same format as you would be able to export from your account's
     * activity view. It includes the following fields: Date, Email Address,
     * Sender, Subject, Status, Tags, Opens, Clicks, Bounce Detail. If you
     * have configured any custom metadata fields, they will be included
     * in the exported data.
     *
     * @param string $notify_email an optional email address to notify when the export job has finished
     * @param string $date_from start date as a UTC string in YYYY-MM-DD HH:MM:SS format
     * @param string $date_to end date as a UTC string in YYYY-MM-DD HH:MM:SS format
     * @param array $tags an array of tag names to narrow the export to; will match messages that contain ANY of the tags
     *     - tags[] string a tag name
     * @param array $senders an array of senders to narrow the export to
     *     - senders[] string a sender address
     * @param array $states an array of states to narrow the export to; messages with ANY of the states will be included
     *     - states[] string a message state
     * @param array $api_keys an array of api keys to narrow the export to; messsagse sent with ANY of the keys will be included
     *     - api_keys[] string an API key associated with your account
     *
     * @return struct information about the activity export job that was started
     *     - id string the unique identifier for this Export. Use this identifier when checking the export job's status
     *     - created_at string the date and time that the export job was created as a UTC string in YYYY-MM-DD HH:MM:SS format
     *     - type string the type of the export job
     *     - finished_at string the date and time that the export job was finished as a UTC string in YYYY-MM-DD HH:MM:SS format, or null for jobs that have not run
     *     - state string the export job's state
     *     - result_url string the url for the export job's results, if the job is complete
     */
    public function activity(
            $notify_email = null,
            $date_from = null,
            $date_to = null,
            $tags = null,
            $senders = null,
            $states = null,
            $api_keys = null
        )
    {
        $params = array(
            'notify_email' => $notify_email,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'tags' => $tags,
            'senders' => $senders,
            'states' => $states,
            'api_keys' => $api_keys
        );

        return $this->client->request('exports/activity', $params);
    }
}