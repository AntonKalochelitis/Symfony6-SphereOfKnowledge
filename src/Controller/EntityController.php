<?php

namespace App\Controller;

use App\DTO\DTOCreate;
use App\DTO\DTOUpdate;
use App\Entity\Entity;
use App\Service\Entity as ServiceEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;

class EntityController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected SerializerInterface    $serializer,
        protected ValidatorInterface     $validator,
        protected ServiceEntity          $serviceEntity,
        protected LoggerInterface        $logger
    )
    {
    }

    #[Route('/api/entity-list', name: 'get_entity_list', methods: ['GET'])]
    #[OA\Tag(name: 'Get Entity list')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'The list of Entity became successful',
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(ref: new Model(type: Entity::class))
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Entity not found',
        content: new OA\JsonContent(
            example: '{"error": string}'
        )
    )]
    public function getEntityList(UserInterface $user): Response
    {
        try {
            $entityList = $this->serviceEntity->getEntityList();

            return $this->json(
                $entityList,
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $error = $e->getMessage();

            $this->logger->error($error);
        }

        return $this->json(
            [
                'error' => $error
            ],
            Response::HTTP_NOT_FOUND
        );
    }

    #[Route('/api/entity/{id}', name: 'get_entity_by_id', methods: ['GET'])]
    #[OA\Tag(name: 'Get Entity by id')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Entity loaded successful',
        content: new Model(type: Entity::class)
    )]
    #[OA\Response(
        response: Response::HTTP_GONE,
        description: 'Entity not found',
        content: new OA\JsonContent(
            example: '{"status": false}'
        )
    )]
    public function getEntityById(int $id, UserInterface $user): Response
    {
        try {
            $entity = $this->serviceEntity->getEntityBy($id);
        } catch (NotFoundHttpException $e) {
            $error = $e->getMessage();

            $this->logger->alert($error);

            return $this->json(
                [
                    'status' => false
                ],
                Response::HTTP_GONE
            );
        }

        if (empty($entity)) {
            $error = 'Entity with ID:' . $id . ' not found';

            $this->logger->alert($error);

            return $this->json(
                [
                    'status' => false
                ],
                Response::HTTP_GONE
            );
        }

        return $this->json(
            $entity,
            Response::HTTP_OK
        );
    }

    #[Route('/api/entity-by-name/{name}', name: 'get_entity_by_name', methods: ['GET'])]
    #[OA\Tag(name: 'Get Entity by name')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Entity loaded successful',
        content: new Model(type: Entity::class)
    )]
    #[OA\Response(
        response: Response::HTTP_GONE,
        description: 'Entity not found',
        content: new OA\JsonContent(
            example: '{"status": false}'
        )
    )]
    public function getEntityByName(string $name, UserInterface $user): Response
    {
        try {
            $entity = $this->serviceEntity->getEntityByName($name);
        } catch (NotFoundHttpException $e) {
            $error = $e->getMessage();

            $this->logger->alert($error);

            return $this->json(
                [
                    'status' => false
                ],
                Response::HTTP_GONE
            );
        }

        if (empty($entity)) {
            $error = 'Entity with Name:' . $name . ' not found';

            $this->logger->alert($error);

            return $this->json(
                [
                    'status' => false
                ],
                Response::HTTP_GONE
            );
        }

        return $this->json(
            $entity,
            Response::HTTP_OK
        );
    }

    #[Route('/api/entity/create', name: 'create_entity', methods: ['POST'])]
    #[OA\Tag(name: 'Create new Entity')]
    #[OA\RequestBody(
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                ref: new Model(type: DTOCreate::class)
            )
        )
    )]
    #[OA\Response(
        response: Response::HTTP_CREATED,
        description: 'Entity successful created',
        content: new Model(type: Entity::class)
    )]
    #[OA\Response(
        response: Response::HTTP_INTERNAL_SERVER_ERROR,
        description: 'Entity not created',
        content: new OA\JsonContent(
            example: '{"status": false}'
        )
    )]
    public function createEntity(Request $request, UserInterface $user): Response
    {
        try {
            /** @var DTOCreate $dto */
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                DTOCreate::class,
                'json'
            );
        } catch (\Exception $e) {
            return $this->json(
                [
                    'status' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(
                $errors,
                Response::HTTP_BAD_REQUEST
            );
        }

        $entity = $this->serviceEntity->createEntity(new Entity(), $dto);

        return $this->json(
            $entity,
            Response::HTTP_CREATED
        );
    }

    #[Route('/api/entity/{id}', name: 'update_entity_by_id', methods: ['PUT'])]
    #[OA\Tag(name: 'Update Entity by id')]
    #[OA\RequestBody(
        content: new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                ref: new Model(type: DTOUpdate::class)
            )
        )
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Entity successful updated',
        content: new Model(type: Entity::class)
    )]
    #[OA\Response(
        response: Response::HTTP_GONE,
        description: 'Entity not found',
        content: new OA\JsonContent(
            example: '{"status": false}'
        )
    )]
    public function updateEntity(int $id, Request $request, UserInterface $user): Response
    {
        try {
            $entity = $this->serviceEntity->getEntityBy($id);
        } catch (NotFoundHttpException $e) {
            $this->logger->alert($e->getMessage());

            return $this->json(
                [
                    'status' => false
                ],
                Response::HTTP_GONE
            );
        }

        if (empty($entity)) {
            $this->logger->alert('Entity with ID:' . $id . ' not found');

            return $this->json(
                [
                    'status' => false
                ],
                Response::HTTP_GONE
            );
        }

        /** @var DTOUpdate $dto */
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            DTOUpdate::class,
            'json'
        );

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(
                $errors,
                Response::HTTP_BAD_REQUEST
            );
        }

        $entity = $this->serviceEntity->updateEntityByEntity($entity, $dto);

        return $this->json(
            $entity,
            Response::HTTP_OK
        );
    }

    #[Route('/api/entity/{id}', name: 'delete_entity_by_id', methods: ['DELETE'])]
    #[OA\Tag(name: 'Delete Entity by id')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Entity deleted successful',
        content: new OA\JsonContent(
            example: '{"status": true}'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_GONE,
        description: 'Entity not found',
        content: new OA\JsonContent(
            example: '{"status": false}'
        )
    )]
    public function deleteEntity(int $id, UserInterface $user): Response
    {
        try {
            $this->serviceEntity->deleteEntityById($id);

            return $this->json(
                [
                    'status' => true
                ],
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $this->logger->alert($e->getMessage());

            return $this->json(
                [
                    'status' => false
                ],
                Response::HTTP_GONE
            );
        }
    }
}
