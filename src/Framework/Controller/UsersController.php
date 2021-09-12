<?php
declare(strict_types=1);

namespace RecruitmentApp\Framework\Controller;

use RecruitmentApp\Domain\Command\UserCommand;
use RecruitmentApp\Domain\Email;
use RecruitmentApp\Domain\User;
use RecruitmentApp\Domain\User\ApiKey;
use RecruitmentApp\Domain\Validator\ValidatorManager;
use RecruitmentApp\Infrastructure\Repository\ArticleRepository;
use RecruitmentApp\Infrastructure\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class UsersController
{
    private MessageBusInterface $messageBus;

    private ValidatorManager $validatorManager;

    private UserRepository $userRepository;

    private ArticleRepository $articleRepository;

    public function __construct(MessageBusInterface $messageBus, ValidatorManager $validatorManager, UserRepository $userRepository, ArticleRepository $articleRepository)
    {
        $this->messageBus = $messageBus;
        $this->validatorManager = $validatorManager;
        $this->userRepository = $userRepository;
        $this->articleRepository = $articleRepository;
    }

    #[Route(path: '/users', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        $userEmail = $request->get('email');
        $errors = $this->validatorManager->verifyEmail($userEmail);
        if (!empty($errors)) {
            return new JsonResponse(
                [
                    'code' => JsonResponse::HTTP_BAD_REQUEST,
                    'message' => $errors,
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
        $user = new User(new Email($userEmail), ApiKey::generate());
        $this->messageBus->dispatch(new UserCommand($user));

        return new JsonResponse(['API_KEY' => $user->jsonSerialize()['api_key']], JsonResponse::HTTP_CREATED);
    }

    #[Route(path: '/users', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(Request $request): JsonResponse
    {
        $apiKey = $request->headers->get('API_KEY');

        $errors = $this->validatorManager->verifyApiKey($apiKey);
        if (!empty($errors)) {
            return new JsonResponse(
                [
                    'code' => JsonResponse::HTTP_BAD_REQUEST,
                    'message' => $errors,
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }
        $this->articleRepository->deleteArticles($apiKey, 0);
        $user = $this->userRepository->findUserByApiKey($apiKey);
        $this->userRepository->deleteUser($user);

        return new JsonResponse([], JsonResponse::HTTP_OK);
    }
}
