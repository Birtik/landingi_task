<?php declare(strict_types=1);

namespace RecruitmentApp\Domain\Command;

use RecruitmentApp\Domain\User;

class UserCommand
{
    private User $user;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
