<?php declare(strict_types=1);

namespace RecruitmentApp\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RecruitmentApp\Domain\User;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param string $apiKey
     *
     * @return User|null
     */
    public function findUserByApiKey(string $apiKey): ?User
    {
        $query = $this->_em->createQuery('SELECT u FROM RecruitmentApp\Domain\User u WHERE u.apiKey.key = :api');

        $query->setParameter('api', $apiKey);

        return $query->getOneOrNullResult();
    }

    /**
     * @param string $email
     *
     * @return User|null
     */
    public function findUserByEmail(string $email): ?User
    {
        $query = $this->_em->createQuery('SELECT u FROM RecruitmentApp\Domain\User u WHERE u.email.email = :email');

        $query->setParameter('email', $email);

        return $query->getOneOrNullResult();
    }

    /**
     * @param User $user
     */
    public function deleteUser(User $user): void
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }
}
