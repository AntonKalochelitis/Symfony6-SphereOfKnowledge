<?php

namespace App\Controller;

use App\DTO\DTOAuthLogin;
use App\DTO\DTOAuthRegister;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\Auth as ServiceAuth;

class AuthController extends AbstractController
{
    public function __construct(
        protected ValidatorInterface       $validator,
        protected SerializerInterface      $serializer,
        protected JWTTokenManagerInterface $jwtManager,
        protected ServiceAuth              $serviceAuth,
    )
    {
    }

    #[Route('/api/user/sign-up', name: 'set_user_sign_up', methods: ['POST'])]
    public function register(Request $request): Response
    {
        try {
            /** @var DTOAuthRegister $dto */
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                DTOAuthRegister::class,
                'json'
            );

            $errors = $this->validator->validate($dto);
            if (count($errors) > 0) {
                return $this->json(
                    $errors,
                    Response::HTTP_BAD_REQUEST
                );
            }

            $user = $this->serviceAuth->register($dto);
        } catch (\Exception $e) {
            return $this->json(
                [
                    'status' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->json([
            'status' => true,
            'user_id' => $user->getId(),
            'token' => $this->jwtManager->create($user),
            'validate_email' => $user->isValidateEmail()
        ]);
    }

    #[Route('/api/user/sign-in', name: 'set_user_sign_in', methods: ['POST'])]
    public function login(): Response // UserInterface $user
    {
        try {
            /** @var User $user  */

        } catch (\Exception $e) {
            return $this->json(
                [
                    'status' => false,
                    'message' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->json([
            'status' => true,
            'user_id' => 0,
            'token' => '',
            'refresh_token' => '',
            'validate_email' => 1
        ]);
    }
}
