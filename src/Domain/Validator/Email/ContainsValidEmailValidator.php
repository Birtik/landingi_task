<?php declare(strict_types=1);

namespace RecruitmentApp\Domain\Validator\Email;

use RecruitmentApp\Infrastructure\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ContainsValidEmailValidator extends ConstraintValidator
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
        if (!$constraint instanceof ContainsValidEmail) {
            throw new UnexpectedTypeException($constraint, ContainsValidEmail::class);
        }

        $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
        if (null !== $value) {
            $user = $this->userRepository->findUserByEmail($value);
        }

        if (null === $value || empty($value)) {
            $this->context->buildViolation($constraint->messageEmptyEmail)->addViolation();
        } elseif (!preg_match($pattern, $value)) {
            $this->context->buildViolation($constraint->messageInvalidEmail)
                ->addViolation();
        } elseif (null !== $user) {
            $this->context->buildViolation($constraint->messageExistedEmail)
                ->addViolation();
        }
    }
}
