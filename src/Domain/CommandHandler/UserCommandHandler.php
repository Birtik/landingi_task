<?php declare(strict_types=1);

namespace RecruitmentApp\Domain\CommandHandler;

use Doctrine\ORM\EntityManagerInterface;
use RecruitmentApp\Domain\Command\UserCommand;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UserCommandHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(UserCommand $userCommand): void
    {
        $user = $userCommand->getUser();
        $this->em->persist($user);
        $this->em->flush();
    }
}
