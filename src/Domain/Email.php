<?php
declare(strict_types=1);

namespace RecruitmentApp\Domain;

use Doctrine\ORM\Mapping as ORM;
use RecruitmentApp\Domain\Validator\Email as CustomAssert;

/**
 * @ORM\Embeddable
 */
class Email
{
    /**
     * @ORM\Column(type = "string")
     * @CustomAssert\ContainsValidEmail()
     */
    private string $email;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
