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
 * Tests for exports.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class ExportsTest extends \PHPUnit_Framework_TestCase
{
    protected $service;
    protected $notifyEmail;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Exports($client);
        $this->notifyEmail = 'notify_email@example.org';
    }

    protected function tearDown()
    {
        unset($this->service);
        unset($this->notifyEmail);
    }

    private function assertMandrillExport($row)
    {
        $this->assertArrayHasKey('id', $row);
        $this->assertArrayHasKey('type', $row);
        $this->assertArrayHasKey('state', $row);
        $this->assertArrayHasKey('result_url', $row);
        $this->assertArrayHasKey('finished_at', $row);
        $this->assertArrayHasKey('created_at', $row);
    }

    private function assertMandrillExportWaiting($row)
    {
        $this->assertMandrillExport($row);
        $this->assertEquals(Model\Export::STATE_WAITING, $row['state']);
    }

    public function testGetListOfExports()
    {
        $response = $this->service->getList();

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertMandrillExport($row);

        return $row['id'];
    }

    /**
     * @depends testGetListOfExports
     */
    public function testGetExportInfo($id)
    {
        $response = $this->service->info($id);

        $this->assertNotEmpty($response);
        $this->assertMandrillExport($response);
    }

    public function testExportOfActivityHistory()
    {
        $email = $this->notifyEmail;

        $dateFrom = '2013-01-01 12:53:01';
        $dateTo   = '2013-01-06 13:42:18';
        $tags     = array('example-tag');
        $senders  = array('test@example.org');
        $states   = array(Model\Message::STATE_SENT);
        $apiKeys  = array('__KEY__');

        $response = $this->service->activity(
            $email,
            $dateFrom,
            $dateTo,
            $tags,
            $senders,
            $states,
            $apiKeys
        );

        $this->assertNotEmpty($response);

        $this->assertMandrillExportWaiting($response);
    }

    public function testExportOfRejectionWhitelist()
    {
        $email = $this->notifyEmail;

        $response = $this->service->whitelist($email);

        $this->assertNotEmpty($response);

        $this->assertMandrillExportWaiting($response);
    }

    public function testExportOfRejectionBlacklist()
    {
        $email = $this->notifyEmail;

        $response = $this->service->rejects($email);

        $this->assertNotEmpty($response);

        $this->assertMandrillExportWaiting($response);
    }
}
?>