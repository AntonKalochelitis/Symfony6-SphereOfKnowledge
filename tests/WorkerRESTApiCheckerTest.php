<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WorkerRESTApiCheckerTest extends WebTestCase
{
//    public function testCreateWorker(): void
//    {
//        $client = static::createClient();
//
//        $data = [
//            'first_name' => 'Test1',
//            'last_name' => 'Test1',
//            'email' => 'test1@gmail.com',
//            'hiring_date' => '2023-10-20',
//            'salary_current' => '10000',
//        ];
//
//        $client->request(
//            'POST',
//            '/api/worker/create',
//            [
//                'json' => $data
//            ]
//        );
//
//        $this->assertResponseIsSuccessful();
////        // Проверка на статус-код 201
////        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
//    }

    public function testGetWorkers(): void
    {
        $client = static::createClient();
        $client->request(
            Request::METHOD_GET,
            '/api/workers'
        );

        $this->assertResponseIsSuccessful();

        $jsonResponse = json_decode(
            $client->getResponse()->getContent(),
            true
        );

        $this->assertEquals($jsonResponse['status'], 'ok');

//        $this->assertResponseHeaderSame(
//            'Content-Type', 'application/json; charset=utf-8'
//        );
//
//        $this->assertJsonContains([
//            '@context' => '/api/contexts/Worker',
//            '@id' => '/api/workers',
//            '@type' => 'hydra:Collection',
//        ]);
    }
}

