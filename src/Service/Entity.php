<?php

namespace App\Service;

use App\DTO\DTOCreate;
use App\DTO\DTOUpdate;
use App\Entity\Entity as EEntity;
use App\Repository\EntityRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;

class Entity
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected EntityRepository       $entityRepository
    )
    {
    }

    /**
     * @return EEntity[]
     */
    public function getEntityList(): array
    {
        return $this->entityRepository->findAll();
    }

    /**
     * @param int $id
     * @return EEntity|null
     */
    public function getEntityBy(int $id): ?EEntity
    {
        return $this->entityRepository->find($id);
    }

    /**
     * @param string $name
     * @return EEntity|null
     */
    public function getEntityByName(string $name): ?EEntity
    {
        return $this->entityRepository->findOneBy([
            'name' => $name
        ]);
    }

    /**
     * @param EEntity $entity
     * @param DTOUpdate $dto
     * @return EEntity
     */
    public function updateEntityByEntity(EEntity $entity, DTOUpdate $dto): EEntity
    {
        if (!empty($dto->getName())) {
            $entity->setName($dto->getName());
        }

        $update = \DateTimeImmutable::createFromMutable(Carbon::createFromTimestamp(time()));

        $entity->setUpdated($update);

        // Заполните сущность данными
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @param EEntity $entity
     * @param DTOCreate $tdo
     * @return EEntity
     */
    public function createEntity(EEntity $entity, DTOCreate $tdo): EEntity
    {
        $entity->setName($tdo->getName());

        $create = \DateTimeImmutable::createFromMutable(Carbon::createFromTimestamp(time()));

        $entity->setUpdated($create);
        $entity->setCreated($create);

        //
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteEntityById(int $id): bool
    {
        try {
            /** @var EEntity $entity */
            $entity = $this->entityRepository->find($id);

            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}