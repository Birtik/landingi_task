<?php
declare(strict_types=1);

namespace Tests\Unit\Domain;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RecruitmentApp\Domain\Email;
use RecruitmentApp\Domain\User;
use RecruitmentApp\Domain\User\ApiKey;
use RecruitmentApp\Domain\Validator\Email\ContainsValidEmail;
use RecruitmentApp\Domain\Validator\Email\ContainsValidEmailValidator;
use RecruitmentApp\Infrastructure\Repository\UserRepository;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class EmailTest extends TestCase
{
    /**
     * @var ContainsValidEmailValidator
     */
    private ContainsValidEmailValidator $containsValidEmailValidator;

    /**
     * @var MockObject|ExecutionContextInterface
     */
    private ExecutionContextInterface|MockObject $executionContextMock;

    /**
     * @var ConstraintViolationBuilderInterface|MockObject
     */
    private ConstraintViolationBuilderInterface|MockObject $constraintViolationBuilderMock;

    /**
     * @var ContainsValidEmail|MockObject
     */
    private ContainsValidEmail|MockObject $constraintMock;

    /**
     * @var MockObject|UserRepository
     */
    private UserRepository|MockObject $userRepositoryMock;

    public function setUp(): void
    {
        $this->userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();

        $this->containsValidEmailValidator = new ContainsValidEmailValidator($this->userRepositoryMock);

        $this->executionContextMock = $this->getMockBuilder(ExecutionContextInterface::class)
        ->disableOriginalConstructor()
        ->getMock();

        $this->constraintViolationBuilderMock = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->constraintMock = $this->getMockBuilder(ContainsValidEmail::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testEmptyEmailIsInvalid(): void
    {
        $email = '';

        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Email cannot be empty!')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->containsValidEmailValidator->initialize($this->executionContextMock);
        $this->containsValidEmailValidator->validate($email, $this->constraintMock);
    }

    public function testEmailWithTwoAtSignsIsInvalid(): void
    {
        $email = 'example@example.com@example.com';

        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Email is invalid!')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->containsValidEmailValidator->initialize($this->executionContextMock);
        $this->containsValidEmailValidator->validate($email, $this->constraintMock);
    }

    public function testEmailWithMissingRecipientIsInvalid(): void
    {
        $email = '@example.com';

        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Email is invalid!')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->containsValidEmailValidator->initialize($this->executionContextMock);
        $this->containsValidEmailValidator->validate($email, $this->constraintMock);
    }

    public function testEmailWithoutDomainAndTopLevelDomainIsInvalid(): void
    {
        $email = 'example@';

        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Email is invalid!')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->containsValidEmailValidator->initialize($this->executionContextMock);
        $this->containsValidEmailValidator->validate($email, $this->constraintMock);
    }

    public function testEmailWithMissingTopLevelDomainIsInvalid(): void
    {
        $email = 'example@example';

        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Email is invalid!')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->containsValidEmailValidator->initialize($this->executionContextMock);
        $this->containsValidEmailValidator->validate($email, $this->constraintMock);
    }

    public function testEmailWithMissingDomainIsInvalid(): void
    {
        $email = 'example@.com';

        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Email is invalid!')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->containsValidEmailValidator->initialize($this->executionContextMock);
        $this->containsValidEmailValidator->validate($email, $this->constraintMock);
    }

    public function testExistedEmail(): void
    {
        $email = 'test@wp.pl';

        $this->userRepositoryMock
            ->expects(self::once())
            ->method('findUserByEmail')
            ->willReturn(new User(new Email($email), ApiKey::generate()));

        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Email is already exist!')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->containsValidEmailValidator->initialize($this->executionContextMock);
        $this->containsValidEmailValidator->validate($email, $this->constraintMock);
    }
}
