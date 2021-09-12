<?php declare(strict_types=1);

namespace RecruitmentApp\Domain\Validator\Email;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsValidEmail extends Constraint
{
    public string $messageEmptyEmail = 'Email cannot be empty!';
    public string $messageInvalidEmail = 'Email is invalid!';
    public string $messageExistedEmail = 'Email is already exist!';
}
