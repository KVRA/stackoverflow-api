<?php

namespace App\Tests;

use App\Service\StackoverflowApi;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class StackoverflowApiTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();
        $container = self::$container;
        $stackoverflowApi = $container->get(StackoverflowApi::class);
        $results = $stackoverflowApi->get('symfony',new DateTime('01/01/2020'),new DateTime('01/04/2020'));
        self::assertEquals(200,$results['status']);
        self::assertArrayHasKey('items',$results['data']);
        self::assertNotEmpty($results['data']['items']);
        self::assertGreaterThan(10,count($results['data']['items']));
        self::assertNotEmpty($results['data']['items'][0]['owner']);
    }
}
