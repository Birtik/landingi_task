<?php declare(strict_types=1);

namespace RecruitmentApp\Domain\Validator\ApiKey;

use RecruitmentApp\Infrastructure\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ContainsValidApiKeyValidator extends ConstraintValidator
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof ContainsValidApiKey) {
            throw new UnexpectedTypeException($constraint, ContainsValidApiKey::class);
        }

        $pattern = "/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i";
        if (null !== $value) {
            $user = $this->userRepository->findUserByApiKey($value);
        }

        if (null === $value || empty($value)) {
            $this->context->buildViolation($constraint->messageEmptyApiKey)->addViolation();
        } elseif (!preg_match($pattern, $value)) {
            $this->context->buildViolation($constraint->messageInvalidApiKey)
                ->addViolation();
        } elseif (null === $user) {
            $this->context->buildViolation($constraint->messageNonExistApiKey)
                ->addViolation();
        }
    }
}
