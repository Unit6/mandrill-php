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
 * Tests for Metadata.
 *
 * @author Unit6 <team@unit6websites.com>
 */
class MetadataTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    protected function setUp()
    {
        global $config;

        $client = new Client($config);

        $this->service = new Service\Metadata($client);
    }

    protected function tearDown()
    {
        unset($this->service);
    }

    public function testAddMetadata()
    {
        // NOTE: there is a delay on delate so repeated runs could
        //       trip over Mandrill's delay in removing deleted metadata.
        $name = 'example_id_' . time();
        $format = '<a href="http://www.example.org/user/{{value}}">{{value}}</a>';

        $metadata = new Model\Metadata();
        $metadata->setName($name);
        $metadata->setViewTemplate($format);

        $response = $this->service->add($metadata);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('state', $response);
        $this->assertArrayHasKey('view_template', $response);

        $this->assertEquals(Model\Metadata::STATE_INDEX, $response['state']);

        return $response['name'];
    }

    /**
     * @depends testAddMetadata
     */
    public function testUpdateMetadata($name)
    {
        $format = '<a href="http://www.example.org/group/{{value}}">{{value}}</a>';

        $metadata = new Model\Metadata();
        $metadata->setName($name);
        $metadata->setViewTemplate($format);

        $response = $this->service->update($metadata);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('state', $response);
        $this->assertArrayHasKey('view_template', $response);
    }

    /**
     * @depends testAddMetadata
     */
    public function testGetListOfMetadata()
    {
        $response = $this->service->getList();

        $this->assertNotEmpty($response);

        $row = $response[0];

        $this->assertArrayHasKey('name', $row);
        $this->assertArrayHasKey('state', $row);
        $this->assertArrayHasKey('view_template', $row);
    }

    /**
     * @depends testAddMetadata
     */
    public function testDeleteMetadata($name)
    {
        $response = $this->service->delete($name);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('state', $response);
        $this->assertArrayHasKey('view_template', $response);
    }
}
?>