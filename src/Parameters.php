<?php
/*
 * This file is part of the Mandrill package.
 *
 * (c) Unit6 <team@unit6websites.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unit6\Mandrill;

/**
 * Parameters class for validating Mandrill models.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class Parameters
{
    public static $typeOptions = array(
        'boolean',
        'integer',
        'float',
        'string',
        'array',
        'object',
        'object',
        'null'
    );

    public static $message = array(
        // the full HTML content to be sent
        'html' => 'string',
        // optional full text content to be sent
        'text' => 'string',
        // the message subject
        'subject' => 'string',
        // the sender email address.
        'from_email' => 'string',
        // optional from name to be used
        'from_name' => 'string',
        // an array of recipient information.
        // - to[] struct a single recipient's information.
        //      - email: string the email address of the recipient
        //      - name: string the optional display name to use for
        //              the recipient
        //      - type: string the header type to use for the recipient,
        //              defaults to "to" if not provided
        'to' => 'array',
        // optional extra headers to add to the message (most headers
        // are allowed)
        'headers' => 'string',
        // whether or not this message is important, and should be
        // delivered ahead of non-important messages
        'important' => 'boolean',
        // whether or not to turn on open tracking for the message
        'track_opens' => 'boolean',
        // whether or not to turn on click tracking for the message
        'track_clicks' => 'boolean',
        // whether or not to automatically generate a text part
        // for messages that are not given text
        'auto_text' => 'boolean',
        // whether or not to automatically generate an HTML part
        // for messages that are not given HTML
        'auto_html' => 'boolean',
        // whether or not to automatically inline all CSS styles
        // provided in the message HTML - only for HTML documents
        // less than 256KB in size
        'inline_css' => 'boolean',
        // whether or not to strip the query string from URLs when
        // aggregating tracked URL data
        'url_strip_qs' => 'boolean',
        // whether or not to expose all recipients in to "To" header
        // for each email
        'preserve_recipients' => 'boolean',
        // set to false to remove content logging for sensitive emails
        'view_content_link' => 'boolean',
        // an optional address to receive an exact copy of each
        // recipient's email email
        'bcc_address' => 'string',
        // a custom domain to use for tracking opens and clicks
        // instead of mandrillapp.com
        'tracking_domain' => 'string',
        // a custom domain to use for SPF/DKIM signing instead
        // of mandrill (for "via" or "on behalf of" in email clients)
        'signing_domain' => 'string',
        // a custom domain to use for the messages's return-path
        'return_path_domain' => 'string',
        // whether to evaluate merge tags in the message. Will
        // automatically be set to true if either merge_vars or
        // global_merge_vars are provided.
        'merge' => 'boolean',
         // the merge tag language to use when evaluating merge
         // tags, either mailchimp or handlebars oneof(mailchimp, handlebars)
        'merge_language' => 'string',
        // global merge variables to use for all recipients. You
        // can override these per recipient.
        //  - global_merge_vars[] struct a single global merge variable
        //      - name: string the global merge variable's name.
        //              Merge variable names are case-insensitive
        //              and may not start with _
        //      - content: mixed the global merge variable's content
        'global_merge_vars' => 'array',
        // per-recipient merge variables, which override global
        // merge variables with the same name.
        //  - merge_vars[] struct per-recipient merge variables
        //      - rcpt: string the email address of the recipient
        //              that the merge variables should apply to
        //      - vars: array the recipient's merge variables
        //          - vars[] struct a single merge variable
        //              - name: string the merge variable's name.
        //                      Merge variable names are case-insensitive
        //                      and may not start with _
        //              - content: mixed the merge variable's content
        'merge_vars' => 'array',
        // an array of string to tag the message with. Stats
        // are accumulated using tags, though we only store
        // the first 100 we see, so this should not be unique
        // or change frequently. Tags should be 50 characters
        // or less. Any tags starting with an underscore are
        // reserved for internal use and will cause errors.
        //  - tags[] string a single tag - must not start with an underscore
        'tags' => 'array',
        // the unique id of a subaccount for this message -
        // must already exist or will fail with an error
        'subaccount' => 'string',
        // an array of strings indicating for which any matching
        // URLs will automatically have Google Analytics parameters
        // appended to their query string automatically.
        'google_analytics_domains' => 'array',
        // optional string indicating the value to set for the
        // utm_campaign tracking parameter. If this isn't provided
        // the email's from address will be used instead.
        'google_analytics_campaign' => 'string',
        // metadata an associative array of user metadata. Mandrill
        // will store this metadata and make it available for
        // retrieval. In addition, you can select up to 10 metadata
        // fields to index and make searchable using the Mandrill
        // search api.
        'metadata' => 'array',
        // Per-recipient metadata that will override the global
        // values specified in the metadata parameter.
        //  - recipient_metadata[] struct metadata for a single recipient
        //      - rcpt: string the email address of the recipient
        //              that the metadata is associated with
        //      - values: array an associated array containing
        //                the recipient's unique metadata. If a key
        //                exists in both the per-recipient metadata
        //                and the global metadata, the per-recipient
        //                metadata will be used.
        'recipient_metadata' => 'array',
        // an array of supported attachments to add to the message
        //  - attachments[] struct a single supported attachment
        //      - type: string the MIME type of the attachment
        //      - name: string the file name of the attachment
        //      - content: string the content of the attachment
        //                 as a base64-encoded string
        'attachments' => 'string',
        // an array of embedded images to add to the message
        //  - images[] struct a single embedded image
        //      - type: string the MIME type of the image
        //              must start with "image/"
        //      - name: string the Content ID of the image
        //              use <img src="cid:THIS_VALUE"> to reference
        //              the image in your HTML content
        //      - content: string the content of the image as
        //                 a base64-encoded string
        'images' => 'array',
        //
        // raw messages:
        //
        // the full MIME document of an email message
        'raw_message' => 'string',
        // a custom domain to use for the messages's return-path
        'return_path_domain' => 'string',

    );

    public static $recipient = array(
        // email address of the recipient
        'email' => 'string',
        // optional display name to use for the recipient
        'name' => 'string',
        // header type to use for the recipient,
        // defaults to "to" if not provided
        'type' => 'string',
        // key/value pairs of metadata.
        'metadata' => 'array',
        // key/value pairs of merge variables.
        'merge_vars' => 'array',
    );

    // NOTE: Used for both embedded images and supported attachments.
    public static $media = array(
        // The MIME type of the file. For images this must
        // start with "image/"
        'type' => 'string',
        // The filename to use. For images this will be
        // the Content ID of the image to reference within
        // your HTML content- use <img src="cid:THIS_VALUE">
        'name' => 'string',
        // the content of the file as a base64-encoded string
        'content' => 'string',
    );

    public static $template = array(
        // the immutable name or slug of a template that exists
        // in the user's account. For backwards-compatibility,
        // the template name may also be used but the immutable
        // slug is preferred.
        'name' => 'string',
        // template content to send. Each item in the array
        // should be a struct with two keys - name: the name
        // of the content block to set the content for, and
        // content: the actual content to put into the block
        //  - content[] the injection of a single piece of content
        //              into a single editable region
        //      - name: the name of the mc:edit editable
        //              region to inject into
        //      - content: the content to inject
        'content' => 'array',
        // default sending address for emails sent using this template
        'from_email' => 'string',
        // default from name to be used
        'from_name' => 'string',
        // default subject line to be used
        'subject' => 'string',
        // the HTML code for the template with mc:edit attributes for
        // the editable elements
        'code' => 'string',
        // default text part to be used when sending with this template
        'text' => 'string',
        // set to false to add a draft template without publishing
        'publish' => 'boolean',
        // optional array of up to 10 labels to use for filtering templates
        'labels' => 'array',
        // optional merge variables to use for injecting merge field
        // content.  If this is not provided, no merge fields will
        // be replaced.
        //  - merge_vars[] struct a single merge variable
        //      - name string the merge variable's name. Merge
        //        variable names are case-insensitive and may not start with _
        //      - content string the merge variable's content
        'merge_vars' => 'array',
    );

    public static $messageQuery = array(
        // search terms to find matching messages
        // http://help.mandrill.com/entries/22211902
        'query' => 'string',
        // start date (YYYY-MM-DD)
        'date_from' => 'string',
        // end date (YYYY-MM-DD)
        'date_to' => 'string',
        // tag names to narrow the search to, will return
        // messages that contain ANY of the tags
        'tags' => 'array',
        // sender addresses to narrow the search to, will
        // return messages sent by ANY of the senders
        'senders' => 'array',
        // API keys to narrow the search to, will return
        // messages sent by ANY of the keys
        'api_keys' => 'array',
        // maximum number of results to return, defaults
        // to 100, 1000 is the maximum
        'limit' => 'integer',
    );

    // for blacklisting and whitelisting specific email addresses.
    public static $address = array(
        // an email address to blacklist/whitelist
        'email' => 'string',
        // an optional comment describing the blacklist/whitelist
        'comment' => 'string',
        // an optional unique identifier for the subaccount to
        // limit the blacklist entry.
        'subaccount' => 'string',
    );

    //
    public static $webhook = array(
        // the unique identifier of a webhook belonging to this account
        'id' => 'string',
        // the URL to POST batches of events
        'url' => 'string',
        // optional description of the webhook
        'description' => 'string',
        // optional list of events that will be posted to the webhook
        //  - events[] string the individual event to listen for
        'events' => 'array',
    );

    //
    public static $subaccount = array(
        // the unique identifier of the subaccount to update
        'id' => 'string',
        // optional display name to further identify the subaccount
        'name' => 'string',
        // optional extra text to associate with the subaccount
        'notes' => 'string',
        // optional manual hourly quota for the subaccount. If
        // not specified, Mandrill will manage this based on reputation
        'custom_quota' => 'integer',
    );

    //
    public static $export = array(
        // the unique identifier for this Export. Use this
        // identifier when checking the export job's status
        'id' => 'string',
        // the type of the export job - activity, reject, or whitelist
        'type' => 'string',
        // the export job's state - waiting, working, complete,
        // error, or expired.
        'state' => 'string',
        // the url for the export job's results, if the job is completed.
        'result_url' => 'string',
        // the date and time that the export job was finished
        // as a UTC string in YYYY-MM-DD HH:MM:SS format
        'finished_at' => 'string',
        // the date and time that the export job was created
        // as a UTC string in YYYY-MM-DD HH:MM:SS format
        'created_at' => 'string',
    );

    //
    public static $metadata = array(
        // a unique identifier for the metadata field
        'name' => 'string',
        // the current state of the metadata field, one
        // of "active", "delete", or "index"
        'state' => 'string',
        // Mustache template to control how the metadata
        // is rendered in your activity log
        'view_template' => 'string',
    );
}