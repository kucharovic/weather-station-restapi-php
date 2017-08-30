<?php

namespace App\Tests\Controller;

use Doctrine\ORM\Tools\SchemaTool;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    private $fixtures;

    public function setUp()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        if (!isset($metadatas)) {
            $metadatas = $em->getMetadataFactory()->getAllMetadata();
        }
        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();
        if (!empty($metadatas)) {
            $schemaTool->createSchema($metadatas);
        }
        $this->postFixtureSetup();

        $fixtures = array(
            'App\Doctrine\DataFixtures\LoadTestData'
        );
        $this->fixtures = $this->loadFixtures($fixtures)->getReferenceRepository();
    }

    /**
     * @expectedException Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     */
    public function testNotAllowedGetIndex()
    {
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(
            Response::HTTP_METHOD_NOT_ALLOWED,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testUnauthorizedPost()
    {
        $client = $this->makeClient();
        $crawler = $client->request('POST', '/');

        $this->assertEquals(
            Response::HTTP_UNAUTHORIZED,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testWrongTokenPost()
    {
        $client = $this->makeClient();
        $crawler = $client->request(
            'POST',
            '/',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => 'SomeDummyToken']
        );

        $this->assertEquals(
            Response::HTTP_FORBIDDEN,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testEmptyPost()
    {
        $client = $this->makeClient();
        $crawler = $client->request(
            'POST',
            '/',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => $this->fixtures->getReference('access')->token]
        );

        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $client->getResponse()->getStatusCode()
        );
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            )
        );
        $this->assertEquals(
            '{"error":{"code":400,"message":"Invalid data","errors":[{"location":"sensor","message":"This value should not be null."},{"location":"datetime","message":"This value should not be null."},{"location":"humidity","message":"This value should not be null."},{"location":"temperature","message":"This value should not be null."}]}}',
            $client->getResponse()->getContent()
        );
    }
    public function testPostWrongData()
    {
        $client = $this->makeClient();
        $crawler = $client->request(
            'POST',
            '/',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => $this->fixtures->getReference('access')->token],
            '{"sensor": "123e4567-e89b-12d3-a456-426655440000"}'
        );
        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $client->getResponse()->getStatusCode()
        );
        $this->assertContains(
            'Sensor with id \u0022123e4567-e89b-12d3-a456-426655440000\u0022 not exists',
            $client->getResponse()->getContent()
        );
    }

    public function testDoubleSubmit()
    {
        $client = $this->makeClient();
        $crawler = $client->request(
            'POST',
            '/',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => $this->fixtures->getReference('access')->token],
<<<EOT
            {
                "sensor":"{$this->fixtures->getReference('sensor')->id}",
                "datetime": "{$this->fixtures->getReference('data')->datetime}",
                "humidity": "{$this->fixtures->getReference('data')->humidity}",
                "temperature": "{$this->fixtures->getReference('data')->temperature}"
            }
EOT
        );
        $this->assertEquals(
            Response::HTTP_CONFLICT,
            $client->getResponse()->getStatusCode()
        );
    }

    public function testSuccessPostData()
    {
        $client = $this->makeClient();
        $crawler = $client->request(
            'POST',
            '/',
            [],
            [],
            ['HTTP_X-AUTH-TOKEN' => $this->fixtures->getReference('access')->token],
<<<EOT
            {
                "sensor":"{$this->fixtures->getReference('sensor')->id}",
                "datetime": "2017-08-23T09:02:22+02:00",
                "humidity": 75.5,
                "temperature": 32.91
            }
EOT
        );
        $this->assertEquals(
            Response::HTTP_CREATED,
            $client->getResponse()->getStatusCode()
        );
    }
}