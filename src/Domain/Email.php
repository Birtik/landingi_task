<?php
declare(strict_types=1);

namespace RecruitmentApp\Domain;

use Doctrine\ORM\Mapping as ORM;
use RecruitmentApp\Domain\Email\Exception\InvalidEmail;
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
//        if (empty($email) || false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
//            throw new InvalidEmail(sprintf('Invalid email: "%s"', $email));
//        }

        $this->email = $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
