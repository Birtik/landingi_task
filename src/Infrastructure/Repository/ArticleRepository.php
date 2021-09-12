<?php declare(strict_types=1);

namespace RecruitmentApp\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RecruitmentApp\Domain\Article;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getAllUserArticlesWithPagination(string $apiKey, int $paginationNumber): array
    {
        $query = $this->_em->createQuery('SELECT a FROM RecruitmentApp\Domain\Article a JOIN a.author u WHERE u.apiKey.key = :api');
        $query->setParameter('api', $apiKey);

        if ($paginationNumber>0) {
            $off = ($paginationNumber - 1) * 5;
            $query->setFirstResult($off);
            $query->setMaxResults(5);
        }

        return $query->getResult();
    }

    public function getArticles(string $apiKey, int $paginationNumber): array
    {
        $articles = $this->getAllUserArticlesWithPagination($apiKey, $paginationNumber);
        $serializedArticles = [];

        foreach ($articles as $article) {
            $serializedArticles[] = $article->jsonSerialize();
        }

        return $serializedArticles;
    }

    public function deleteArticles(string $apiKey, int $paginationNumber): void
    {
        $articles = $this->getAllUserArticlesWithPagination($apiKey, $paginationNumber);

        foreach ($articles as $article) {
            $this->_em->remove($article);
        }
        $this->_em->flush();
    }
}
