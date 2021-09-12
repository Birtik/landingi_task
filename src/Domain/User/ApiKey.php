<?php
declare(strict_types=1);

namespace RecruitmentApp\Domain\User;

use Doctrine\ORM\Mapping as ORM;
use RecruitmentApp\Domain\Validator\ApiKey as CustomAssert;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Embeddable
 */
class ApiKey
{
    /**
     * @ORM\Column(type = "string", name="api_key")
     * @CustomAssert\ContainsValidApiKey()
     */
    private ?string $key;

    public static function generate(): self
    {
        return new self((string) Uuid::v4());
    }

    public function __construct(?string $key)
    {
        $this->key = $key;
    }

    public function __toString(): string
    {
        return $this->key;
    }
}
