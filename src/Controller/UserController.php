<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\User as ServiceUsers;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class UserController extends AbstractController
{
    public function __construct(
        protected ServiceUsers $serviceUsers,
        protected UserRepository  $userRepository,
        protected LoggerInterface $logger
    )
    {
    }

    #[Route('/api/users', name: 'get_user_list', methods: ['GET'])]
    #[OA\Tag(name: 'Get user list')]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'The list of user became successful',
        content: new OA\JsonContent(
            type: "array",
            items: new OA\Items(ref: new Model(type: User::class))
        )
    )]
    #[OA\Response(
        response: Response::HTTP_NOT_FOUND,
        description: 'Workers not found',
        content: new OA\JsonContent(
            example: "{'error': string}"
        )
    )]
    public function getUsers()
    {
        try {
            $users = $this->serviceUsers->getUserList();

            return $this->json(
                $users,
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

    #[Route('/api/user/{id}', name: 'get_user_by_id', methods: ['GET'])]
    public function getUserById(int $id)
    {
        $user = $this->serviceUsers->getUserBy($id);

        return $this->json(
            $user,
            Response::HTTP_OK
        );
    }
}
