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
 * Tests for adding, updating, publishing and deleting a template.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class TemplatesTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Templates($client);
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    /**
     * Add a new template.
     */
    public function testAddTemplate()
    {
        $name = 'TemplatesTest';

        $template = new Model\Template();
        $template->setName($name);
        $template->setFromEmail('john.smith@example.org');
        $template->setFromName('John Smith');
        $template->setSubject('Example from John Smith');
        $template->setCode('<div>Example HTML content: <p mc:edit="test-section">editable content</p></div>');
        $template->setText('Example text content');
        $template->setPublish(false);
        $template->setLabels(array('foobar-label'));

        $response = $this->service->add($template);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('slug', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('subject', $response);
        $this->assertArrayHasKey('code', $response);
        $this->assertArrayHasKey('text', $response);
        $this->assertArrayHasKey('from_email', $response);
        $this->assertArrayHasKey('from_name', $response);
        $this->assertArrayHasKey('publish_code', $response);
        $this->assertArrayHasKey('publish_text', $response);
        $this->assertArrayHasKey('publish_name', $response);
        $this->assertArrayHasKey('publish_subject', $response);
        $this->assertArrayHasKey('publish_from_email', $response);
        $this->assertArrayHasKey('publish_from_name', $response);
        $this->assertArrayHasKey('labels', $response);
        $this->assertArrayHasKey('updated_at', $response);
        $this->assertArrayHasKey('created_at', $response);

        return $name;
    }

    /**
     * Get the information for an existing template.
     *
     * @depends testAddTemplate
     */
    public function testGetTemplateInfo($name)
    {
        $response = $this->service->info($name);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('slug', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('subject', $response);
        $this->assertArrayHasKey('code', $response);
        $this->assertArrayHasKey('text', $response);
        $this->assertArrayHasKey('from_email', $response);
        $this->assertArrayHasKey('from_name', $response);
        $this->assertArrayHasKey('publish_from_email', $response);
        $this->assertArrayHasKey('publish_from_name', $response);
        $this->assertArrayHasKey('publish_name', $response);
        $this->assertArrayHasKey('publish_subject', $response);
        $this->assertArrayHasKey('labels', $response);
        $this->assertArrayHasKey('updated_at', $response);
        $this->assertArrayHasKey('created_at', $response);
    }

    /**
     * Return a list of all the templates available to this user.
     *
     * @depends testAddTemplate
     */
    public function testGetTemplateList()
    {
        $response = $this->service->getList();

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('slug', $row);
        $this->assertArrayHasKey('name', $row);
        $this->assertArrayHasKey('subject', $row);
        $this->assertArrayHasKey('code', $row);
        $this->assertArrayHasKey('text', $row);
        $this->assertArrayHasKey('from_email', $row);
        $this->assertArrayHasKey('from_name', $row);
        $this->assertArrayHasKey('publish_from_email', $row);
        $this->assertArrayHasKey('publish_from_name', $row);
        $this->assertArrayHasKey('publish_name', $row);
        $this->assertArrayHasKey('publish_subject', $row);
        $this->assertArrayHasKey('labels', $row);
        $this->assertArrayHasKey('updated_at', $row);
        $this->assertArrayHasKey('created_at', $row);
    }

    /**
     * Update the code for an existing template. If null is
     * provided for any fields, the values will remain unchanged.
     *
     * @depends testAddTemplate
     */
    public function testUpdateTemplate($name)
    {
        $email = 'jane.doe@example.com';

        $template = new Model\Template();
        $template->setName($name);
        $template->setFromEmail($email);

        $response = $this->service->update($template);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('slug', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('subject', $response);
        $this->assertArrayHasKey('code', $response);
        $this->assertArrayHasKey('text', $response);
        $this->assertArrayHasKey('from_email', $response);
        $this->assertArrayHasKey('from_name', $response);
        $this->assertArrayHasKey('draft_updated_at', $response);
        $this->assertArrayHasKey('publish_from_email', $response);
        $this->assertArrayHasKey('publish_from_name', $response);
        $this->assertArrayHasKey('publish_name', $response);
        $this->assertArrayHasKey('publish_subject', $response);
        $this->assertArrayHasKey('published_at', $response);
        $this->assertArrayHasKey('labels', $response);
        $this->assertArrayHasKey('updated_at', $response);
        $this->assertArrayHasKey('created_at', $response);

        $this->assertEquals($email, $response['from_email']);
    }

    /**
     * Publish the content for the template. Any new messages
     * sent using this template will start using the content
     * that was previously in draft.
     *
     * @depends testAddTemplate
     */
    public function testPublishTemplate($name)
    {
        $response = $this->service->publish($name);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('slug', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('subject', $response);
        $this->assertArrayHasKey('code', $response);
        $this->assertArrayHasKey('text', $response);
        $this->assertArrayHasKey('from_email', $response);
        $this->assertArrayHasKey('from_name', $response);
        $this->assertArrayHasKey('draft_updated_at', $response);
        $this->assertArrayHasKey('publish_from_email', $response);
        $this->assertArrayHasKey('publish_from_name', $response);
        $this->assertArrayHasKey('publish_name', $response);
        $this->assertArrayHasKey('publish_subject', $response);
        $this->assertArrayHasKey('published_at', $response);
        $this->assertArrayHasKey('labels', $response);
        $this->assertArrayHasKey('updated_at', $response);
        $this->assertArrayHasKey('created_at', $response);

        return $response['name'];
    }

    /**
     * Return the recent history (hourly stats for the last 30 days)
     * for a template.
     *
     * @depends testPublishTemplate
     */
    public function testGetTemplateTimeSeries($name)
    {
        $response = $this->service->timeSeries($name);

        if (empty($response)) {
            $this->markTestSkipped('Template new. It has no history.');
        }

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('sent', $row);
        $this->assertArrayHasKey('hard_bounces', $row);
        $this->assertArrayHasKey('soft_bounces', $row);
        $this->assertArrayHasKey('rejects', $row);
        $this->assertArrayHasKey('complaints', $row);
        $this->assertArrayHasKey('unsubs', $row);
        $this->assertArrayHasKey('opens', $row);
        $this->assertArrayHasKey('clicks', $row);
        $this->assertArrayHasKey('unique_opens', $row);
        $this->assertArrayHasKey('unique_clicks', $row);
        $this->assertArrayHasKey('time', $row);
    }

    /**
     * Inject content and optionally merge fields into a
     * template, returning the HTML that results.
     *
     * @depends testPublishTemplate
     */
    public function testRenderTemplate($name)
    {
        $mergeTag = 'foobar';
        $mergeValue = 'Hello World!';

        $format  = 'Template Render says "%s"';
        $target  = sprintf( $format, $mergeValue );
        $content = sprintf( $format, '*|' . strtoupper( $mergeTag ) . '|*' );

        $template = new Model\Template();

        $template->setName($name);
        $template->addContent('test-section', $content);
        $template->addMergeVar($mergeTag, $mergeValue);

        $response = $this->service->render($template);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('html', $response);

        $html = $response['html'];

        $this->assertGreaterThan(0, strpos($html, $target));
    }

    /**
     * Delete a template.
     *
     * @depends testAddTemplate
     */
    public function testDeleteTemplate($name)
    {
        $response = $this->service->delete($name);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('slug', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('subject', $response);
        $this->assertArrayHasKey('code', $response);
        $this->assertArrayHasKey('text', $response);
        $this->assertArrayHasKey('from_email', $response);
        $this->assertArrayHasKey('from_name', $response);
        $this->assertArrayHasKey('publish_code', $response);
        $this->assertArrayHasKey('publish_text', $response);
        $this->assertArrayHasKey('publish_name', $response);
        $this->assertArrayHasKey('publish_subject', $response);
        $this->assertArrayHasKey('publish_from_email', $response);
        $this->assertArrayHasKey('publish_from_name', $response);
        $this->assertArrayHasKey('labels', $response);
        $this->assertArrayHasKey('updated_at', $response);
        $this->assertArrayHasKey('created_at', $response);

        return $name;
    }

    /**
     * @depends testDeleteTemplate
     * @expectedException Unit6\Mandrill\Exception\TemplateUnknown
     */
    public function testExceptionOnDeleteNonExistingTemplate($name)
    {
        $response = $this->service->delete($name);
    }
}
?>