<?php declare(strict_types=1);

namespace RecruitmentApp\Domain\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use RecruitmentApp\Domain\Command\ArticleCommand;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ArticleCommandHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(ArticleCommand $articleCommand): void
    {
        $article = $articleCommand->getArticle();
        $this->em->persist($article);
        $this->em->flush();
    }
}
