<?php
declare(strict_types=1);

namespace Tests\Unit\Domain\User;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RecruitmentApp\Domain\User\ApiKey;
use RecruitmentApp\Domain\Validator\ApiKey\ContainsValidApiKey;
use RecruitmentApp\Domain\Validator\ApiKey\ContainsValidApiKeyValidator;
use RecruitmentApp\Infrastructure\Repository\UserRepository;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class ApiKeyTest extends TestCase
{
    /**
     * @var ContainsValidApiKeyValidator
     */
    private ContainsValidApiKeyValidator $containsValidApiKeyValidator;

    /**
     * @var MockObject|ExecutionContextInterface
     */
    private ExecutionContextInterface|MockObject $executionContextMock;

    /**
     * @var ConstraintViolationBuilderInterface|MockObject
     */
    private ConstraintViolationBuilderInterface|MockObject $constraintViolationBuilderMock;

    /**
     * @var ContainsValidApiKey|MockObject
     */
    private ContainsValidApiKey|MockObject $constraintMock;

    /**
     * @var MockObject|UserRepository
     */
    private UserRepository|MockObject $userRepositoryMock;

    public function setUp(): void
    {
        $this->userRepositoryMock = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();

        $this->containsValidApiKeyValidator = new ContainsValidApiKeyValidator($this->userRepositoryMock);

        $this->executionContextMock = $this->getMockBuilder(ExecutionContextInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->constraintViolationBuilderMock = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->constraintMock = $this->getMockBuilder(ContainsValidApiKey::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testEmptyApiKeyIsInvalid(): void
    {
        $apiKey = '';

        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Api-Key cannot be empty!')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->containsValidApiKeyValidator->initialize($this->executionContextMock);
        $this->containsValidApiKeyValidator->validate($apiKey, $this->constraintMock);
    }

    public function testNonExistApiKey(): void
    {
        $apiKey = ApiKey::generate();

        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('User with specific Api-Key do not exist!')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->containsValidApiKeyValidator->initialize($this->executionContextMock);
        $this->containsValidApiKeyValidator->validate((string) $apiKey, $this->constraintMock);
    }

    public function testValidFirstPartApiKey(): void
    {
        $apiKey = '7e6bf3651-b528-4f5f-8f53-5cf43ec288ba';

        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Api-Key is invalid!')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->containsValidApiKeyValidator->initialize($this->executionContextMock);
        $this->containsValidApiKeyValidator->validate($apiKey, $this->constraintMock);
    }

    public function testValidSecondPartApiKey(): void
    {
        $apiKey = '7e6bf365-b5282-4f5f-8f53-5cf43ec288ba';

        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Api-Key is invalid!')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->containsValidApiKeyValidator->initialize($this->executionContextMock);
        $this->containsValidApiKeyValidator->validate($apiKey, $this->constraintMock);
    }

    public function testValidThirdPartApiKey(): void
    {
        $apiKey = '7e6bf365-b528-4f5f3-8f53-5cf43ec288ba';

        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Api-Key is invalid!')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->containsValidApiKeyValidator->initialize($this->executionContextMock);
        $this->containsValidApiKeyValidator->validate($apiKey, $this->constraintMock);
    }

    public function testValidFourthPartApiKey(): void
    {
        $apiKey = '7e6bf365-b528-4f5f-8f534-5cf43ec288ba';

        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Api-Key is invalid!')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->containsValidApiKeyValidator->initialize($this->executionContextMock);
        $this->containsValidApiKeyValidator->validate($apiKey, $this->constraintMock);
    }

    public function testValidFifthPartApiKey(): void
    {
        $apiKey = '7e6bf365-b528-4f5f3-8f53-5cf43ec288ba4';

        $this->executionContextMock
            ->expects(self::once())
            ->method('buildViolation')
            ->with('Api-Key is invalid!')
            ->willReturn($this->constraintViolationBuilderMock);

        $this->constraintViolationBuilderMock
            ->expects(self::once())
            ->method('addViolation')
            ->willReturn(null);

        $this->containsValidApiKeyValidator->initialize($this->executionContextMock);
        $this->containsValidApiKeyValidator->validate($apiKey, $this->constraintMock);
    }

    public function testGeneratesUniqueApiKeys(): void
    {
        self::assertNotEquals((string) ApiKey::generate(), (string) ApiKey::generate());
    }
}
