<?php declare(strict_types=1);

namespace RecruitmentApp\Domain\Command;

use RecruitmentApp\Domain\Article;

class ArticleCommand
{
    private Article $article;

    /**
     * @return Article
     */
    public function getArticle(): Article
    {
        return $this->article;
    }

    public function __construct(Article $article)
    {
        $this->article = $article;
    }
}
