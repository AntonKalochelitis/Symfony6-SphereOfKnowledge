<?php

namespace App\Controller;

use App\DTO\DTOCreate;
use App\DTO\DTOUpdate;
use App\Entity\Workers;
use App\Repository\WorkersRepository;
use App\Service\Workers as ServiceWorker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;
use OpenApi\Annotations as OAS;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Psr\Log\LoggerInterface;

class WorkersController extends AbstractController
{
    protected WorkersRepository $workersRepository;
    protected EntityManagerInterface $entityManager;
    protected SerializerInterface $serializer;
    protected ValidatorInterface $validator;
    protected ServiceWorker $serviceWorker;
    protected LoggerInterface $logger;

    public function __construct(
        WorkersRepository      $workersRepository,
        EntityManagerInterface $entityManager,
        SerializerInterface    $serializer,
        ValidatorInterface     $validator,
        ServiceWorker          $serviceWorker,
        LoggerInterface        $logger
    )
    {
        $this->workersRepository = $workersRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->serviceWorker = $serviceWorker;
        $this->logger = $logger;
    }

    #[Route('/api/workers', name: 'get_worker_list', methods: ['GET'])]
    #[OA\Tag(name: 'Get worker list')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'The list of worker became successful',
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(ref: new Model(type: Workers::class))
        )
    )]
    public function getWorkers()
    {
        try {
            $workers = $this->workersRepository->findAll();

            return $this->json(
                $workers,
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());

            return $this->json(
                $e->getMessage(),
                Response::HTTP_NOT_FOUND
            );
        }
    }

    #[Route('/api/worker/{id}', name: 'get_worker_by_id', methods: ['GET'])]
    #[OA\Tag(name: 'Get worker by id')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Worker loaded successful',
        content: new Model(type: Workers::class)
    )]
    #[OA\Response(
        response: Response::HTTP_GONE,
        description: 'Worker not found',
        content: new OA\JsonContent(
            example: '{"status": false}'
        )
    )]
    public function getWorker(int $id)
    {
        try {
            $worker = $this->workersRepository->find($id);
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
        content: new Model(type: Workers::class)
    )]
    public function createWorker(Request $request)
    {
        /** @var DTOCreate $dto */
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            DTOCreate::class,
            'json'
        );

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            return $this->json(
                $errors,
                Response::HTTP_BAD_REQUEST
            );
        }

        $worker = $this->serviceWorker->create(new Workers(), $dto);

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
        content: new Model(type: Workers::class)
    )]
    #[OA\Response(
        response: Response::HTTP_GONE,
        description: 'Worker not found',
        content: new OA\JsonContent(
            example: '{"status": false}'
        )
    )]
    public function updateWorker(Request $request, int $id)
    {
        try {
            $worker = $this->workersRepository->find($id);
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

        $worker = $this->serviceWorker->update($worker, $dto);

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
    public function deleteWorker(int $id)
    {
        /** @var Workers $worker */
        $worker = $this->workersRepository->find($id);

        try {
            $this->entityManager->remove($worker);
            $this->entityManager->flush();

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
