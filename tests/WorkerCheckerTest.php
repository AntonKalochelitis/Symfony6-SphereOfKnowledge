<?php

namespace App\Tests;

use App\Entity\Worker;
use App\Repository\WorkersRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WorkerCheckerTest extends KernelTestCase
{
    private function createMockWorker($worker, $hiringDate): Worker
    {
        $worker->setId(1000000);
        $worker->setFirstName('Test1');
        $worker->setLastName('Test1');
        $worker->setEmail('test1@gmail.com');
        $worker->setHiringDate($hiringDate);
        $worker->setSalaryCurrent(10000);

        return $worker;
    }

    public function testCreateWorker(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $worker = new Worker();

        $this->assertInstanceOf(Worker::class, $worker);

        $hiringDate = \DateTimeImmutable::createFromMutable(Carbon::createFromTimestamp(time()));
        $worker = $this->createMockWorker($worker, $hiringDate);

        $entityManager->persist($worker);
        $entityManager->flush();

        $this->assertEquals(1000000, $worker->getId());
        $this->assertEquals('Test1', $worker->getFirstName());
        $this->assertEquals('Test1', $worker->getLastName());
        $this->assertEquals('test1@gmail.com', $worker->getEmail());
        $this->assertEquals($hiringDate, $worker->getHiringDate());
        $this->assertEquals('10000', $worker->getSalaryCurrent());
    }

    public function testUpdateWorker(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $worker = new Worker();

        $this->assertInstanceOf(Worker::class, $worker);

        $hiringDate = \DateTimeImmutable::createFromMutable(Carbon::createFromTimestamp(time()));
        $worker = $this->createMockWorker($worker, $hiringDate);

        $entityManager->persist($worker);
        $entityManager->flush();

        $worker->setFirstName('Test2');
        $worker->setLastName('Test2');
        $worker->setEmail('test2@gmail.com');
        $worker->setHiringDate($hiringDate);
        $worker->setSalaryCurrent(10000);

        $entityManager->persist($worker);
        $entityManager->flush();

        $this->assertEquals(1000000, $worker->getId());
        $this->assertEquals('Test2', $worker->getFirstName());
        $this->assertEquals('Test2', $worker->getLastName());
        $this->assertEquals('test2@gmail.com', $worker->getEmail());
        $this->assertEquals($hiringDate, $worker->getHiringDate());
        $this->assertEquals('10000', $worker->getSalaryCurrent());
    }

    public function testDeleteWorker(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $worker = new Worker();

        $this->assertInstanceOf(Worker::class, $worker);

        $hiringDate = \DateTimeImmutable::createFromMutable(Carbon::createFromTimestamp(time()));
        $worker = $this->createMockWorker($worker, $hiringDate);

        $entityManager->persist($worker);
        $entityManager->flush();

        // Проверяем, что сущность была успешно добавлена
        $this->assertEquals(1000000, $worker->getId());

        // Удаляем сущность
        $entityManager->remove($worker);
        $entityManager->flush();

        // Пробуем найти сущность в репозитории
        $workersRepository = $this->createMock(WorkersRepository::class);
        $workerFromRepository = $workersRepository->find($worker->getId());

        // Убеждаемся, что сущность больше не существует
        $this->assertNull($workerFromRepository);
    }
}
