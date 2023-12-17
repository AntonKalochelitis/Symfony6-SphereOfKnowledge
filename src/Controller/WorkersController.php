<?php

namespace App\Controller;

use App\DTO\DTOCreate;
use App\DTO\DTOUpdate;
use App\Entity\Worker;
use App\Service\Workers as ServiceWorker;
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

class WorkersController extends AbstractController
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected SerializerInterface    $serializer,
        protected ValidatorInterface     $validator,
        protected ServiceWorker          $serviceWorker,
        protected LoggerInterface        $logger
    )
    {
    }

    #[Route('/api/workers', name: 'get_worker_list', methods: ['GET'])]
    #[OA\Tag(name: 'Get worker list')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'The list of worker became successful',
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(ref: new Model(type: Worker::class))
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Workers not found',
        content: new OA\JsonContent(
            example: '{"error": string}'
        )
    )]
    public function getWorkers(UserInterface $user): Response
    {
        dd($user);
        try {

            $workers = $this->serviceWorker->getWorgerList();

            return $this->json(
                $workers,
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

    #[Route('/api/worker/{id}', name: 'get_worker_by_id', methods: ['GET'])]
    #[OA\Tag(name: 'Get worker by id')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Worker loaded successful',
        content: new Model(type: Worker::class)
    )]
    #[OA\Response(
        response: Response::HTTP_GONE,
        description: 'Worker not found',
        content: new OA\JsonContent(
            example: '{"status": false}'
        )
    )]
    public function getWorkerById(int $id): Response
    {
        try {
            $worker = $this->serviceWorker->getWorgerBy($id);
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

        if (empty($worker)) {
            $error = 'Worker with ID:' . $id . ' not found';

            $this->logger->alert($error);

            return $this->json(
                [
                    'status' => false
                ],
                Response::HTTP_GONE
            );
        }

        return $this->json(
            $worker,
            Response::HTTP_OK
        );
    }

    #[Route('/api/worker/create', name: 'create_worker', methods: ['POST'])]
    #[OA\Tag(name: 'Create new worker')]
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
        description: 'Worker successful created',
        content: new Model(type: Worker::class)
    )]
    #[OA\Response(
        response: Response::HTTP_INTERNAL_SERVER_ERROR,
        description: 'Worker not created',
        content: new OA\JsonContent(
            example: '{"status": false}'
        )
    )]
    public function createWorker(Request $request): Response
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

        $worker = $this->serviceWorker->createWorker(new Worker(), $dto);

        return $this->json(
            $worker,
            Response::HTTP_CREATED
        );
    }

    #[Route('/api/worker/{id}', name: 'update_worker_by_id', methods: ['PUT'])]
    #[OA\Tag(name: 'Update worker by id')]
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
        description: 'Worker successful updated',
        content: new Model(type: Worker::class)
    )]
    #[OA\Response(
        response: Response::HTTP_GONE,
        description: 'Worker not found',
        content: new OA\JsonContent(
            example: '{"status": false}'
        )
    )]
    public function updateWorker(Request $request, int $id): Response
    {
        try {
            $worker = $this->serviceWorker->getWorgerBy($id);
        } catch (NotFoundHttpException $e) {
            $this->logger->alert($e->getMessage());

            return $this->json(
                [
                    'status' => false
                ],
                Response::HTTP_GONE
            );
        }

        if (empty($worker)) {
            $this->logger->alert('Worker with ID:' . $id . ' not found');

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

        $worker = $this->serviceWorker->updateWorkerByWorker($worker, $dto);

        return $this->json(
            $worker,
            Response::HTTP_OK
        );
    }

    #[Route('/api/worker/{id}', name: 'delete_worker_by_id', methods: ['DELETE'])]
    #[OA\Tag(name: 'Delete worker by id')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Worker deleted successful',
        content: new OA\JsonContent(
            example: '{"status": true}'
        )
    )]
    #[OA\Response(
        response: Response::HTTP_GONE,
        description: 'Worker not found',
        content: new OA\JsonContent(
            example: '{"status": false}'
        )
    )]
    public function deleteWorker(int $id): Response
    {
        try {
            $this->serviceWorker->deleteWorkerById($id);

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
