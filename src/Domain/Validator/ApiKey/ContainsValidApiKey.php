<?php declare(strict_types=1);

namespace RecruitmentApp\Domain\Validator\ApiKey;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsValidApiKey extends Constraint
{
    public string $messageEmptyApiKey = 'Api-Key cannot be empty!';
    public string $messageInvalidApiKey = 'Api-Key is invalid!';
    public string $messageNonExistApiKey = 'User with specific Api-Key do not exist!';
}
