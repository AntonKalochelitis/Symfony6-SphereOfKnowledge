<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Carbon\Carbon;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WorkerRESTApiCheckerTest extends WebTestCase
{
    protected function getWorkerById($client, $id): void
    {
        $client->request(
            Request::METHOD_GET,
            '/api/worker/' . $id
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    protected function setUpdateById($client, $id)
    {
        $client->request(
            Request::METHOD_GET,
            '/api/worker/' . $id
        );

        $hiringDate = Carbon::now()->addDays(3)->format('Y-m-d');

        $arr = [
            'first_name' => 'Test2',
            'last_name' => 'Test2',
            'email' => 'test2@gmail.com',
            'hiring_date' => $hiringDate,
            'salary_current' => '10000',
        ];

        $headers = [
            'Content-Type' => 'application/json'
        ];

        $client->request(
            Request::METHOD_PUT,
            '/api/worker/' . $id,
            ['headers' => $headers],
            [],
            [],
            json_encode($arr, JSON_UNESCAPED_UNICODE)
        );

        $this->assertResponseIsSuccessful();

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $jsonResponse = json_decode(
            $client->getResponse()->getContent(),
            true
        );

//        $id = $jsonResponse['id'];

        $this->assertArrayHasKey('id', $jsonResponse, 'The response should contain an "id" field');
        $this->assertArrayHasKey('firstName', $jsonResponse, 'The response should contain a "first_name" field');
        $this->assertArrayHasKey('lastName', $jsonResponse, 'The response should contain a "last_name" field');
        $this->assertArrayHasKey('email', $jsonResponse, 'The response should contain an "email" field');
        $this->assertArrayHasKey('hiringDate', $jsonResponse, 'The response should contain a "hiring_date" field');
        $this->assertArrayHasKey('salaryCurrent', $jsonResponse, 'The response should contain a "salary_current" field');

        $this->assertEquals('Test2', $jsonResponse['firstName'], 'The "first_name" should match the expected value');
        $this->assertEquals('Test2', $jsonResponse['lastName'], 'The "last_name" should match the expected value');
        $this->assertEquals('test2@gmail.com', $jsonResponse['email'], 'The "email" should match the expected value');
        $this->assertEquals($hiringDate . 'T00:00:00+00:00', $jsonResponse['hiringDate'], 'The "hiring_date" should match the expected value');
        $this->assertEquals('10000', $jsonResponse['salaryCurrent'], 'The "salary_current" should match the expected value');
    }

    protected function setDeleteById($client, $id)
    {
        $headers = [
            'Content-Type' => 'application/json'
        ];

        $client->request(
            Request::METHOD_DELETE,
            '/api/worker/' . $id,
            ['headers' => $headers]
        );

        $this->assertResponseIsSuccessful();
    }

    public function testCreateWorker(): void
    {
        $client = static::createClient();

        $arr = [
            'first_name' => 'Test1',
            'last_name' => 'Test1',
            'email' => 'test1@gmail.com',
            'hiring_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'salary_current' => '10000',
        ];

        $headers = [
            'Content-Type' => 'application/json'
        ];

        $client->request(
            Request::METHOD_POST,
            '/api/worker/create',
            ['headers' => $headers],
            [],
            [],
            json_encode($arr, JSON_UNESCAPED_UNICODE)
        );

        $this->assertResponseIsSuccessful();

        $jsonResponse = json_decode(
            $client->getResponse()->getContent(),
            true
        );

        $id = $jsonResponse['id'];

        // Проверяем Get /api/worker/{id}
        $this->getWorkerById($client, $id);

        // Проверяем Update /api/worker/{id}
        $this->setUpdateById($client, $id);

        // Проверяем DELETE /api/worker/{id}
        $this->setDeleteById($client, $id);
    }
}