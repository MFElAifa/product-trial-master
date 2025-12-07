<?php

namespace App\Controller;

use App\DTO\CreateUserRequest;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\UserService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
class UserController extends AbstractController
{

    public function __construct(
        private readonly UserService $userService,
        private readonly ObjectMapperInterface $mapper,
        protected UserPasswordHasherInterface $encoder
    ) {}

    #[Route('/account ', name: 'api.account.create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(#[MapRequestPayload()] CreateUserRequest $request): JsonResponse
    {
        $user = $this->mapper->map($request, User::class);
        $password = $this->encoder->hashPassword($user, $request->password);
        $user->setPassword($password);

        $user = $this->userService->saveUser($user);
        return $this->json($user);
    }


    #[Route('/token', name: 'api.account.token', methods: ['POST'])]
    public function token(
        Request $request,
        UserRepository $userRepository,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);
        //dd($data);
        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->json(['error' => 'Email et mot de passe requis'], 400);
        }

        $user = $userRepository->findOneBy(['email' => $data['email']]);

        if (!$user || !$this->encoder->isPasswordValid($user, $data['password'])) {
            return $this->json(['error' => 'Identifiants invalides'], 401);
        }

        $token = $jwtManager->create($user);

        return $this->json(['token' => $token]);
    }
}
