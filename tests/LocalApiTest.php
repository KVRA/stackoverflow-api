<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

class LocalApiTest extends TestCase
{
    /**
     * @var string
     */
    private $api_url;

    public function setUp(): void
    {
        $this->api_url = "http://localhost:8000/api/query";
    }

    public function testTagged(): void
    {

        $response = $httpClient = HttpClient::create()->request(
            'GET',
            $this->api_url,
            [
                'query'=> [
                    'tagged'=>'symfony'
                ]
            ]
        );
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200,$statusCode);
        $contentType = $response->getHeaders()['content-type'][0];
        $this->assertEquals('application/json',$contentType);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('items',$content);
        $this->assertNotEmpty($content['items']);
        $this->assertNotEmpty($content['items'][0]['owner']);
    }

    public function testTaggedNoResults() {
        $response = $httpClient = HttpClient::create()->request(
            'GET',
            $this->api_url,
            [
                'query'=> [
                    'tagged'=>'mksdnngkernerkmsx'
                ]
            ]
        );
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200,$statusCode);
        $contentType = $response->getHeaders()['content-type'][0];
        $this->assertEquals('application/json',$contentType);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('items',$content);
        $this->assertEmpty($content['items']);
    }
    public function testTaggedSymfony2020HasResults() {
        $response = $httpClient = HttpClient::create()->request(
            'GET',
            $this->api_url,
            [
                'query'=> [
                    'tagged'=>'symfony',
                    'fromdate'=>[
                        'day'=>1,
                        'month'=>1,
                        'year'=>2020
                    ]
                ]
            ]
        );
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200,$statusCode);
        $contentType = $response->getHeaders()['content-type'][0];
        $this->assertEquals('application/json',$contentType);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('items',$content);
        $this->assertNotEmpty($content['items']);
        $this->assertNotEmpty($content['items'][0]['owner']);
    }

    public function testInvalidDate(): void
    {

        $response = $httpClient = HttpClient::create()->request(
            'GET',
            $this->api_url,
            [
                'query'=> [
                    'tagged'=>'symfony',
                    'fromdate'=>[
                        'day'=>1,
                        'month'=>1,
                        'year'=>2020
                    ]
                ]
            ]
        );
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200,$statusCode);
        $contentType = $response->getHeaders()['content-type'][0];
        $this->assertEquals('application/json',$contentType);
        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('items',$content);
        $this->assertNotEmpty($content['items']);
        $this->assertGreaterThan(10,count($content['items']));
        $this->assertNotEmpty($content['items'][0]['owner']);
    }
}
