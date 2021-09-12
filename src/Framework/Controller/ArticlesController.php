<?php declare(strict_types=1);

namespace RecruitmentApp\Framework\Controller;

use RecruitmentApp\Domain\Article;
use RecruitmentApp\Domain\Command\ArticleCommand;
use RecruitmentApp\Domain\Validator\ValidatorManager;
use RecruitmentApp\Infrastructure\Repository\ArticleRepository;
use RecruitmentApp\Infrastructure\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController
{
    private UserRepository $userRepository;

    private ArticleRepository $articleRepository;

    private ValidatorManager $validatorManager;

    private MessageBusInterface $messageBus;

    public function __construct(ValidatorManager $validatorManager, MessageBusInterface $messageBus, UserRepository $userRepository, ArticleRepository $articleRepository)
    {
        $this->userRepository = $userRepository;
        $this->articleRepository = $articleRepository;
        $this->messageBus = $messageBus;
        $this->validatorManager = $validatorManager;
    }

    #[Route(path: '/articles', name: 'create_article', methods: ['POST'])]
    public function createArticle(Request $request): JsonResponse
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
        $user = $this->userRepository->findUserByApiKey($apiKey);
        $title = $request->get('title');
        $content = $request->get('content');
        $this->messageBus->dispatch(new ArticleCommand(new Article($user, $title, $content)));

        return new JsonResponse([], JsonResponse::HTTP_CREATED);
    }

    #[Route(path: '/articles', name: 'get_articles', methods: ['GET'])]
    public function getArticles(Request $request): JsonResponse
    {
        $paginationNumber = $request->query->get('limit') ?? 0;
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
        $articles = $this->articleRepository->getArticles($apiKey, (int) $paginationNumber);

        return new JsonResponse($articles, JsonResponse::HTTP_OK);
    }
}
