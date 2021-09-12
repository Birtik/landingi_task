<?php declare(strict_types=1);

namespace RecruitmentApp\Domain\Validator;

use RecruitmentApp\Domain\Email;
use RecruitmentApp\Domain\User\ApiKey;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorManager
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param string|null $userEmail;
     *
     * @return array
     */
    public function verifyEmail(?string $userEmail): array
    {
        $errors = $this->collectErrorForEmail($userEmail);

        return $this->prepareErrorResponseData($errors);
    }

    /**
     * @param string|null $api
     *
     * @return array
     */
    public function verifyApiKey(?string $api): array
    {
        $errors = $this->collectErrorForApiKey($api);

        return $this->prepareErrorResponseData($errors);
    }

    /**
     * @param string|null $api
     *
     * @return ConstraintViolationListInterface
     */
    public function collectErrorForApiKey(?string $api): ConstraintViolationListInterface
    {
        $apiKey = new ApiKey($api);

        return $this->validator->validate($apiKey);
    }

    /**
     * @param string|null $userEmail
     *
     * @return ConstraintViolationListInterface
     */
    public function collectErrorForEmail(?string $userEmail): ConstraintViolationListInterface
    {
        $email = new Email($userEmail);

        return $this->validator->validate($email);
    }

    /**
     * @param ConstraintViolationListInterface $errors
     *
     * @return array
     */
    public function prepareErrorResponseData(ConstraintViolationListInterface $errors): array
    {
        $e = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $e[] = $error->getMessage();
        }

        return $e;
    }
}
