<?php

namespace App\Service;

use App\DTO\DTOCreate;
use App\DTO\DTOUpdate;
use App\Entity\Workers as EntityWorker;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;

class Workers
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
    }

    public function create(EntityWorker $worker, DTOCreate $tdo): EntityWorker
    {
        $worker->setFirstName($tdo->getFirstName());
        $worker->setLastName($tdo->getLastName());
        $worker->setEmail($tdo->getEmail());
        $worker->setHiringDate($tdo->getHiringDate());
        $worker->setSalaryCurrent($tdo->getSalaryCurrent());

        $create = \DateTimeImmutable::createFromMutable(Carbon::createFromTimestamp(time()));

        $worker->setUpdated($create);
        $worker->setCreated($create);

        // Заполните сущность Worker данными из $data
        $this->entityManager->persist($worker);
        $this->entityManager->flush();

        return $worker;
    }

    public function update(EntityWorker $worker, DTOUpdate $dto): EntityWorker
    {
        if (!empty($dto->getFirstName())) {
            $worker->setFirstName($dto->getFirstName());
        }

        if (!empty($dto->getLastName())) {
            $worker->setLastName($dto->getLastName());
        }

        if (!empty($dto->getEmail())) {
            $worker->setEmail($dto->getEmail());
        }

        if (!empty($dto->getSalaryCurrent())) {
            $worker->setSalaryCurrent($dto->getSalaryCurrent());
        }

        if (!empty($dto->getHiringDate())) {
            $worker->setHiringDate($dto->getHiringDate());
        }

        $update = \DateTimeImmutable::createFromMutable(Carbon::createFromTimestamp(time()));

        $worker->setUpdated($update);

        // Заполните сущность Worker данными
        $this->entityManager->persist($worker);
        $this->entityManager->flush();

        return $worker;
    }
}